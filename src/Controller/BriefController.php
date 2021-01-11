<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\BriefApprenant;
use App\Entity\BriefMaPromo;
use App\Entity\LivrableAttendu;
use App\Entity\LivrableAttenduApprenant;
use App\Entity\Niveaux;
use App\Entity\Promo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Brief;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Formateur;
use App\Entity\Groupe;
use App\Entity\Ressource;
use App\Entity\Tag;
use App\Entity\EtatBriefGroupe;

class BriefController extends AbstractController
{
    /**
     * @Route("/brief", name="brief")
     */
    public function index(): Response
    {
        return $this->render('brief/index.html.twig', [
            'controller_name' => 'BriefController',
        ]);
    }

    private $mailer;
    public function __construct( \Swift_Mailer $mailer)
    {
        $this->mailer=$mailer;
    }


    /**
     * @Route(
     *  name="add_brief",
     *  path="/api/formateurs/brief",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\Controller\Brief::addBrief",
     *
     *      "_api_collection_operation_name"="add_brief"
     *  }
     * )
     */
    public function addBrief(SerializerInterface $serializer,Request $request, EntityManagerInterface $manager)
    {
        $brief = $request->request->all();
        $img = $request->files->get("imagePromos");
        $img = fopen($img->getRealPath(), "rb");
        $tag = explode(",", $brief['Tag']);
        $formateurs = $brief['formateur'];
        $niveau = explode(",", $brief['niveaux']);
        $ressources = explode(",", $brief['ressource']);
        $entityManager = $this->getDoctrine()->getManager();
        $BriefObject = $serializer->denormalize($brief, Brief::class);
        $BriefObject->setImagePromos($img);

        foreach($tag as $key){
            $BriefObject->addTag($entityManager->getRepository(Tag::class)->find($key));
        }
        foreach($niveau as $key){
            $BriefObject->addNiveau($entityManager->getRepository(Niveaux::class)->find($key));
        }
        foreach($ressources as $key){
            $BriefObject->addRessource($entityManager->getRepository(Ressource::class)->find($key));
        }
        $BriefObject->setFormateurs($entityManager->getRepository(Formateur::class)->find($formateurs));
        $manager->persist($BriefObject);
        if (isset($brief['groupe']) && !empty($brief['groupe'])){
            $groupe = explode(",", $brief['groupe']);
            foreach($groupe as $key){
                $grp = $entityManager->getRepository(Groupe::class)->find($key);
                if ($grp) {
                    $etatBrGr = new EtatBriefGroupe;
                    $etatBrGr->setGroupe($grp);
                    $etatBrGr->setBrief($BriefObject);
                    $manager->persist($etatBrGr);
                    $briefPromo = new BriefMaPromo();
                    $briefPromo->setBrief($BriefObject);
                    $briefPromo->setPromo($grp->getPromo());
                    $manager->persist($briefPromo);
                }
            }
        }
        $manager->flush();
        fclose($img);
        return $this->json($BriefObject,Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="dupliquer_brief",
     *  path="/api/formateurs/brief/{id}",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\Controller\Brief::DuplBrief",
     *      "_api_collection_operation_name"="dupliquer_brief"
     *  }
     * )
     */
    public function DuplBrief(SerializerInterface $serializer, $id, EntityManagerInterface $manager)
    {
        $Brief = $manager->getRepository(Brief::class)->find($id);
        $formateurs = $Brief->getFormateurs();
        $img = $Brief->getImagePromo();
        $briefTab=$serializer->decode($serializer->serialize($Brief,"json"),"json");
        $briefTab['id'] = null;
        $briefTab['formateurs'] = null;
        $brief = $serializer->denormalize($briefTab, Brief::class, true);
        $brief->setFormateurs($formateurs);
        $brief->setImagePromos($img);
        $manager->persist($brief);
        $manager->flush();
        return $this->json("success",Response::HTTP_OK);

    }

    /**
     * @Route(
     *  name="affecter_brief",
     *  path="/api/formateurs/promo/{idPr}/brief/{idBr}/assignation",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\Brief::affecterBrief",
     *      "_api_collection_operation_name"="affecter_brief"
     *  }
     * )
     */
    public function affecterBrief($idPr, $idBr, SerializerInterface $serializer,Request $request, EntityManagerInterface $manager)
    {
        $Brief = $manager->getRepository(Brief::class)->find($idBr);
        $Promos = $manager->getRepository(Promo::class)->find($idPr);
        $JsonObject = $request->getContent();
        $JsonObject = $serializer->decode($JsonObject,"json");
        $brmapromo = $manager->getRepository(BriefMaPromo::class)->findOneBy(["promo"=>$idPr, "brief"=>$idBr]);
        if (!$Brief || !$Promos) {
            throw $this->createNotFoundException(
                'le Brief ou la promo n\'existe pas'
            );
        }
        if (!$brmapromo) {
            throw $this->createNotFoundException(
                'Le brief dont l\'id = '.$idBr.' n\'est pas dans le promos '.$idPr
            );
        }
        if (isset($JsonObject['action'])) {
            if ($JsonObject['action'] === "affecter") {
                if (isset($JsonObject['groupe'])) {
                    foreach ($JsonObject['groupe'] as $grp) {
                        $grpObject = $manager->getRepository(Groupe::class)->find($grp["id"]);
                        if ($grpObject && $grpObject->getStatus() !== "fermer") {
                            $etatBriefGroupe = new EtatBriefGroupe();
                            $etatBriefGroupe->setGroupe($grpObject);
                            $etatBriefGroupe->setBrief($Brief);
                            foreach ($grpObject->getApprenant() as $app) {
                                $mail = (new \Swift_Message("Affectation Brief"));
                                $mail->setFrom("syfadilou3@gmail.com")
                                    ->setTo($app->getEmail())
                                    ->setSubject("Affectation d'un brief")
                                    ->setBody("Bonjour Cher(e) " . $app->getPrenom() . " " . $app->getNom() . "
                                        le brief " . $Brief->getNomBrief() . " vous a été affecter");

                                $this->mailer->send($mail);
                            }
                            $manager->persist($etatBriefGroupe);
                        }
                    }
                }
                if (isset($JsonObject['apprenant'])) {
                    $Apprenant = $manager->getRepository(Apprenant::class)->find($JsonObject['apprenant']["id"]);
                    if ($Apprenant && !$Apprenant->getArchiver()) {
                        $BriefApprenant = new BriefApprenant();
                        $BriefApprenant->setApprenant($Apprenant);
                        $BriefApprenant->setBriefMaPromo($brmapromo);
                        $mail = (new \Swift_Message("Affectation Brief"));
                        $mail->setFrom("syfadilou3@gmail.com")
                            ->setTo($Apprenant->getEmail())
                            ->setSubject("Affectation d'un brief")
                            ->setBody("Bonjour Cher(e) " . $Apprenant->getPrenom() . " " . $Apprenant->getNom() . "
                              le brief " . $Brief->getNomBrief() . " vous a été affecter");
                        $this->mailer->send($mail);
                        $manager->persist($BriefApprenant);
                    }
                }
                $Brief->setEtatBrouillonsAssigneValide("assigner");
            } else {
                if (isset($JsonObject['apprenant'])) {
                    $BriefApprenant = $manager->getRepository(BriefApprenant::class)->findOneBy(["apprenant" => $JsonObject['apprenant']["id"], "briefMaPromo" => $brmapromo->getId()]);
                    $manager->remove($BriefApprenant);
                }
                if (isset($JsonObject['groupe'])) {
                    $etatBriefGroupe = $manager->getRepository(EtatBriefGroupe::class)->findOneBy(["groupe" => $JsonObject["groupe"]["id"], "brief" => $idBr]);
                    $manager->remove($etatBriefGroupe);
                }
            }
        }else{
            return $this->json("Designer l'action à faire",Response::HTTP_BAD_REQUEST);
        }
        $manager->flush();
        return $this->json("success",Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="put_brief",
     *  path="/api/formateurs/promo/{idPr}/brief/{idBr}",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\Brief::putBrief",
     *      "_api_collection_operation_name"="put_brief"
     *  }
     * )
     */
    public function putBrief($idPr, $idBr, SerializerInterface $serializer,Request $request, EntityManagerInterface $manager)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Brief = $entityManager->getRepository(Brief::class)->find($idBr);
        $Promos = $entityManager->getRepository(Promo::class)->find($idPr);
        $JsonBrief = $serializer->decode($request->getContent(), 'json');
        $brmapromo = $manager->getRepository(BriefMaPromo::class)->findOneBy(["promo"=>$idPr, "brief"=>$idBr]);
        if (!$Brief) {
            throw $this->createNotFoundException(
                'Pas de Brief Pour l\'id '.$idBr
            );
        }
        if (!$Promos) {
            throw $this->createNotFoundException(
                'Pas de Promos Pour l\'id '.$idPr
            );
        }
        if (!$brmapromo) {
            throw $this->createNotFoundException(
                'Le brief '.$idBr.' n\'est pas dans la promo '.$idPr
            );
        }
        if ($JsonBrief["archiver"]){
            $Brief->setArchiver($JsonBrief["archiver"]);
        }
        if ($JsonBrief["etat"]){
            $Brief->setEtat($JsonBrief["etat"]);
        }
        if ($JsonBrief["niveaux"]) {
            foreach ($Brief->getNiveau() as $niveau){
                $Brief->removeNiveau($niveau);
            }
            foreach($JsonBrief["niveaux"] as $niveau)
            {
                $Brief->addNiveau($manager->getRepository(Niveaux::class)->find($niveau["id"]));
            }
        }
        if ($JsonBrief["tags"]) {
            foreach ($Brief->getTag() as $tag){
                $Brief->removeTag($tag);
            }
            foreach($JsonBrief["tags"] as $tag)
            {
                $Brief->addTag($manager->getRepository(Tag::class)->find($tag["id"]));
            }
        }
        if ($JsonBrief["ressources"]) {
            foreach($Brief->getRessources() as $res)
            {
                $Brief->removeRessource($res);
            }
            foreach($JsonBrief["ressources"] as $res)
            {
                $Brief->addRessource($manager->getRepository(Ressource::class)->find($res["id"]));
            }
        }
        if ($JsonBrief["livrableAttendus"]) {
            foreach ($Brief->getLivrableAttendus() as $LA){
                $Brief->removeLivrableAttendu($LA);
            }
            foreach($JsonBrief["livrableAttendus"] as $lA)
            {
                $Brief->addNiveau($manager->getRepository(LivrableAttendu::class)->find($lA["id"]));
            }
        }
        $manager->flush();
        return $this->json("success",Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="add_url",
     *  path="/api/apprenants/{idAp}/groupe/{idGr}/livrables",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\Controller\Brief::AddUrl",
     *      "_api_collection_operation_name"="add_url"
     *  }
     * )
     */
    public function AddUrl($idAp, $idGr, SerializerInterface $serializer,Request $request, EntityManagerInterface $manager)
    {
        $Apprenant = $manager->getRepository(Apprenant::class)->find($idAp);
        $Groupe = $manager->getRepository(Groupe::class)->find($idGr);
        $postman= $serializer->decode($request->getContent(), 'json');
        if (!$Apprenant) {
            throw $this->createNotFoundException(
                'Pas de Apprenant Pour l\'id '.$idAp
            );
        }
        if (!$Groupe) {
            throw $this->createNotFoundException(
                'Pas de Groupe Pour l\'id '.$idGr
            );
        }
        if(count($manager->getRepository(Groupe::class)->findGroupeByApp($idAp, $idGr)) == 0){
            throw $this->createNotFoundException(
                'L\'apprenant dont l\'id = '.$idAp.' n\'est pas dans le Groupe '.$idGr
            );
        }
        foreach ($Apprenant->getLivrableAttendu() as $apLA){
            foreach($Groupe->getApprenant() as $apprenant){
                $lAA = new LivrableAttenduApprenant();
                $lAA->setApprenant($apprenant)
                    ->setUrl($postman["url"])
                    ->setLivrableAttendu($apLA->getLivrableAttendu());
                $manager->persist($lAA);
            }
        }
        $manager->flush();
        return $this->json("success",Response::HTTP_OK);

    }
}
