<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Groupe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     *  name="add_apprenant",
     *  path="api/admin/groupes/{id}",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\Groupe::addApp",
     *      "_api_collection_operation_name"="put"
     *  }
     * )
     */
    public function addApp($id, SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $grpe = $menager->getRepository(Groupe::class)->find($id);
        if (!$grpe) {
            throw $this->createNotFoundException(
                'Pas de groupe Pour l\'id '.$id
            );
        }
        $grpeJson= $request->getContent();
        $grpeJson= $serializer->decode($grpeJson,"json");
        if(count($grpeJson["apprenant"])){
            foreach($grpeJson["apprenant"] as $app){
                if($menager->getRepository(Apprenant::class)->find($app)){
                    $grpe->addApprenant($menager->getRepository(Apprenant::class)->find($app));
                }                    
            }
        }
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }
}
