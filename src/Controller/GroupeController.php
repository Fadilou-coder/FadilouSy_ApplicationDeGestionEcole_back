<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Groupe;
use App\Entity\Promo;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

class GroupeController extends AbstractController
{
    /**
     * @Route("/groupe", name="groupe")
     */
    public function index(): Response
    {
        return $this->render('groupe/index.html.twig', [
            'controller_name' => 'GroupeController',
        ]);
    }

    /**
     * @Route(
     *  name="add_groupe",
     *  path="api/admin/groupes",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\Controller\Groupe::PostGroupe",
     *      "_api_collection_operation_name"="post"
     *  }
     * )
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $menager
     * @param Request $request
     * @return JsonResponse
     */
    public function PostGroupe(ValidatorService $validate, SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $grpeJson = $serializer->decode($request->getContent(),"json");
        $grpeObject = new Groupe();
        $promo = $menager->getRepository(Promo::class)->find($grpeJson["promo"]);
        $grpPrincipale = $menager->getRepository(Groupe::class)->findOneBy(["type" => "PRINCIPALE".$grpeJson["promo"]]);
        $grpeObject -> setType($grpeJson["type"])
                    -> setNom($grpeJson["nom"]);
        if(count($grpeJson["apprenants"])){
            foreach($grpeJson["apprenants"] as $app){
                if($menager->getRepository(Apprenant::class)->find($app["id"])){
                    $grpeObject->addApprenant($menager->getRepository(Apprenant::class)->find($app["id"]));
                    $grpPrincipale->addApprenant($menager->getRepository(Apprenant::class)->find($app["id"]));
                }
            }
        }
        if(count($grpeJson["formateurs"])){
            foreach($grpeJson["formateurs"] as $for){
                if($menager->getRepository(Formateur::class)->find($for["id"])){
                    $grpeObject->addFormateur($menager->getRepository(Formateur::class)->find($for["id"]));
                }
            }
        }
        if($menager->getRepository(Formateur::class)->find($grpeJson["promo"])){
            $grpeObject->setPromo($promo);
        }
        $validate->validate($grpeObject);
        $menager->persist($grpeObject);
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="add_apprenant",
     *  path="api/admin/groupes/{id}",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\Groupe::addApp",
     *      "_api_collection_operation_name"="put"
     *  }
     * )
     * @param $id
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $menager
     * @param Request $request
     * @return JsonResponse
     */
    public function addApp($id, SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $grpe = $menager->getRepository(Groupe::class)->find($id);
        $promo = $grpe->getPromo();
        $grpPrincipale = $menager->getRepository(Groupe::class)->findOneBy(["type" => "PRINCIPALE".$promo->getId()]);
        if (!$grpe) {
            throw $this->createNotFoundException(
                'Pas de groupe Pour l\'id '.$id
            );
        }
        $grpeJson= $serializer->decode($request->getContent(),"json");
        if(count($grpeJson["apprenant"])){
            foreach($grpeJson["apprenant"] as $app){
                if($menager->getRepository(Apprenant::class)->find($app["id"])){
                    $grpe->addApprenant($menager->getRepository(Apprenant::class)->find($app["id"]));
                    if ($grpe->getType() !== "PRINCIPALE".$promo->getId()){
                        $grpPrincipale->addApprenant($menager->getRepository(Apprenant::class)->find($app["id"]));
                    }
                }                    
            }
        }
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="del_apprenant",
     *  path="api/admin/groupes/{id}/apprenants/{Id}",
     *  methods={"DELETE"},
     *  defaults={
     *      "_controller"="\app\Controller\Groupe::delApp",
     *      "_api_collection_operation_name"="delete"
     *  }
     * )
     * @param $id
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $menager
     * @param Request $request
     * @return JsonResponse
     */
    public function delApp($id, $Id, SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $groupe = $menager->getRepository(Groupe::class)->find($id);
        $apprenant = $menager->getRepository(Apprenant::class)->find($Id);
        if (!$groupe) {
            throw $this->createNotFoundException(
                'Pas de groupe Pour l\'id '.$id
            );
        }
        if (!$apprenant) {
            throw $this->createNotFoundException(
                'Pas de groupe Pour l\'id '.$id
            );
        }
        $groupe->removeApprenant($apprenant);
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }
}
