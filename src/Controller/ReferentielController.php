<?php

namespace App\Controller;

use App\Entity\GrpeCompetences;
use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

class ReferentielController extends AbstractController
{
    /**
     * @Route("/referentiel", name="referentiel")
     */
    public function index(): Response
    {
        return $this->render('referentiel/index.html.twig', [
            'controller_name' => 'ReferentielController',
        ]);
    }

    /**
     * @Route(
     *  name="put_grpecompt",
     *  path="api/admin/referentiels/{id}",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\Referentiel::putGrpe",
     *      "_api_collection_operation_name"="put"
     *  }
     * )
     */
    public function putGrpe($id, SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ref = $entityManager->getRepository(Referentiel::class)->find($id);

        if (!$ref) {
            throw $this->createNotFoundException(
                'Pas de Referentiel Pour l\'id '.$id
            );
        }
        $grpeCompts = $request->getContent();
        $grpeCompts = $serializer->decode($grpeCompts,"json");
        if(count($grpeCompts["grpecompetences"])){
            if($grpeCompts["action"]==="ajouter"){
                foreach($grpeCompts["grpecompetences"] as $grpecompt){
                    $ref->addGrpeCompetence($entityManager->getRepository(GrpeCompetences::class)->find($grpecompt));
                }
            }else{
                foreach($grpeCompts["grpecompetences"] as $grpecompt){
                    $ref->removeGrpeCompetence($entityManager->getRepository(GrpeCompetences::class)->find($grpecompt));
                }
            }
        }
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }
}
