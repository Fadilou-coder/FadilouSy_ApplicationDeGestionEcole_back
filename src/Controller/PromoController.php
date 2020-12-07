<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Groupe;
use App\Entity\Promo;
use App\Entity\Referentiel;
use App\Entity\User;
use App\Repository\PromoRepository;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use foo\Foo;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PromoController extends AbstractController
{
    /**
     * PromoController constructor.
     */
    private $mailer;
    public function __construct( \Swift_Mailer $mailer)
    {
        $this->mailer=$mailer;
    }

    /**
     * @Route("/promo", name="promo")
     */
    public function index(): Response
    {
        return $this->render('promo/index.html.twig', [
            'controller_name' => 'PromoController',
        ]);
    }


    /**
     * @Route(
     *  name="add_supp_app",
     *  path="api/admin/promos/{id}/apprenants",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\Groupe::AddSuppApp",
     *      "_api_item_operation_name"="put_apprenant"
     *  }
     * )
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $menager
     * @param Request $request
     * @return JsonResponse
     */
    public function AddSuppApp($id, SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $promo = $menager->getRepository(Promo::class)->find($id);
        $promoJon = $serializer->decode($request->getContent(), "json");
        if ($promoJon["action"] === "ajouter"){
            foreach ($promoJon["apprenants"] as $app => $id_app){
                $appr = $menager->getRepository(Apprenant::class)->find($id_app);
                $appr->addGroupe($menager->getRepository(Groupe::class)->findOneBy(['type'=>'PRINCIPALE'.$id]));

            }
        }else{
            foreach ($promoJon["apprenants"] as $app){
                $appr = $menager->getRepository(Apprenant::class)->find($app["id"]);
                $grps = $menager->getRepository(Groupe::class)->findGroupe($app["id"]);
                foreach ($grps as $g){
                    $g->removeApprenant($appr);
                }
            }
        }
        dd($promo);
        $menager->flush();
        return $this->json($promo,Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="add_supp_for",
     *  path="api/admin/promos/{id}/formateurs",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\Groupe::AddSuppFor",
     *      "_api_item_operation_name"="put_formateur"
     *  }
     * )
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $menager
     * @param Request $request
     * @return JsonResponse
     */
    public function AddSuppFor($id, SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $promo = $menager->getRepository(Promo::class)->find($id);
        $promoJon = $serializer->decode($request->getContent(), "json");
        if ($promoJon["action"] === "ajouter"){
            foreach ($promoJon["formateurs"] as $for => $id_for){
                $promo->addFormateur($menager->getRepository(Formateur::class)->find($id_for));
            }
        }else{
            foreach ($promoJon["formateurs"] as $for => $id_for){
                $promo->removeFormateur($menager->getRepository(Formateur::class)->find($id_for));
            }
        }
        dd($promo);
        $menager->flush();
        return $this->json($promo,Response::HTTP_OK);
    }

}
