<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Profil;
use App\Entity\Promo;
use App\Entity\Referentiel;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
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
     *  name="add_promo",
     *  path="/api/admin/promos",
     *  methods={"POST"},
     * )
     */
    public function addPromo(\Swift_Mailer $swift_Mailer, SerializerInterface $serializer,Request $request, ValidatorService $validate, EntityManagerInterface $menager)
    {
        $myMail="syfadilou3@gmail.com";
        $to = [];
        $promo = $request->request->all();
        $img = $request->files->get("image");
        if ($request->files->get("fichier")) {
            $fichier = $request->files->get("fichier")->getRealPath();
        }
        //dd($fichier);
        if($img){
            $img = fopen($img->getRealPath(), "rb");
        }
        $promoObject = $serializer->denormalize($promo, Promo::class);
        $promoObject->setImage($img);
        $refs = explode(",", $promo['refs']);
        foreach($refs as $r){
            if ($r !== "") {
                $promoObject->addReferentiel($menager->getRepository(Referentiel::class)->findOneBy(['libelle' => $r]));
            }
        }
        
        $apps = explode(",", $promo['apps']);
        foreach($apps as $app){
            if ($app !== "") {
                if ($menager->getRepository(Apprenant::class)->findOneBy(['email' => $app])) {
                    $promoObject->addApprenant($menager->getRepository(Apprenant::class)->findOneBy(['email' => $app]));
                }else {
                    $nouvApp = new Apprenant($app);
                    $nouvApp->setProfil($menager->getRepository(Profil::class)->findOneBy(['libelle' => 'APPRENANT']));
                    $promoObject->addApprenant($nouvApp);
                }
                $to[] = $app;
            }
        }

        if (isset($fichier)) {
            $inputFileType = \PHPExcel_IOFactory::identify($fichier);
            /*  Create a new Reader of the type defined in $inputFileType  */
            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($fichier);
            $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
            //extract to a PHP readable array format
            foreach ($cell_collection as $cell) {
                $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                //header will/should be in row 1 only. of course this can be modified to suit your need.
                if ($row == 1) {
                    $header[] = $data_value;
                } else {
                    $values[$row][] = $data_value;
                }
            }
            //dd($values[2]);
            //send the data in an array format
            foreach($values as $val){
                    for ($i=0; $i < count($val) ; $i++) { 
                        $data[$header[$i]][] = $val[$i];
                    }
            }

            foreach($data['email'] as $app){
                if (!$menager->getRepository(Apprenant::class)->findOneBy(['email' => $app])) {
                    $nouvApp = new Apprenant($app);
                    $nouvApp->setProfil($menager->getRepository(Profil::class)->findOneBy(['libelle' => 'APPRENANT']));
                    $promoObject->addApprenant($nouvApp);
                }
                $to[] = $app;
            }
        }
        if (count($to)) {
            $mail= (new \Swift_Message("Sélection Sonatel Academy"));
            $mail->setFrom($myMail)
                ->setTo($to)
                ->setSubject("SONATEL ACADEMY RESULTATS SELECTION")
                ->setBody("Bonjour Cher(e) apprenant,\nFélicitations!!! vous avez été sélectionné(e) suite à votre test dentré à la Sonatel Academy.\nVeuillez utiliser ces informations pour vous connecter à votre promos.\nEmail: ".$app."\nPassword: bienvenu.\n A bientot!!!")
                ;
            $swift_Mailer->send($mail);
        }
        $validate->validate($promoObject);
        $menager->persist($promoObject);
        $menager->flush();
        return $this->json($promoObject,Response::HTTP_OK);
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
        //dd($promo);
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
