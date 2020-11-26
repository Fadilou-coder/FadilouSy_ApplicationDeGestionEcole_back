<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Entity\GrpeCompetences;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GrpeCompetencesController extends AbstractController
{


    /**
     * @Route("/grpe/competences", name="grpe_competences")
     */
    public function index(): Response
    {
        return $this->render('grpe_competences/index.html.twig', [
            'controller_name' => 'GrpeCompetencesController',
        ]);
    }


    /**
     * @Route(
     *  name="put_compt",
     *  path="api/admin/grpecompetences/{id}",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\GrpeCompetences::putCompt",
     *      "_api_collection_operation_name"="put"
     *  }
     * )
     */
    public function putCompt($id, SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $grpe = $menager->getRepository(GrpeCompetences::class)->find($id);
        if (!$grpe) {
            throw $this->createNotFoundException(
                'Pas de Groupe de competences Pour l\'id '.$id
            );
        }
        $grpecompt = $request->getContent();
        $grpecompt = $serializer->decode($grpecompt,"json");
        if(count($grpecompt["competences"])){
            if($grpecompt["action"]==="ajouter"){
                foreach($grpecompt["competences"] as $comp){
                    $grpe->addCompetence($menager->getRepository(Competences::class)->find($comp));
                }
            }else{
                foreach($grpecompt["competences"] as $comp){
                    $grpe->removeCompetence($menager->getRepository(Competences::class)->find($comp));
                }
            }
        }
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="add_grpecompetences",
     *  path="/api/admin/grpecompetences",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\Controller\GrpeCompetences::addgrpeCompt",
     *      "_api_collection_operation_name"="post_groupe_competences"
     *  }
     * )
     */
    public function addgrpeCompt(SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $grpecompt = $serializer->decode($request->getContent(),"json");
        $grpeObject = new GrpeCompetences();
        $grpeObject ->setLibelle($grpecompt["libelle"])
                    ->setDescriptif($grpecompt["descriptif"]);
        foreach($grpecompt["competences"] as $compt){
            if(isset($compt["id"])){
                $grpeObject->addCompetence($menager->getRepository(Competences::class)->find($compt["id"]));
            }
            else{
                $competence =  new Competences();
                $competence->setLibelle($compt["libelle"]);
                $menager->persist($competence);
                $grpeObject->addCompetence($competence);
            }
        }
        $menager->persist($grpeObject);
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }
    
}
