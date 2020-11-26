<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Entity\Niveaux;
use App\Service\CompetenceService;
use App\Service\TagService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

class CompetencesController extends AbstractController
{
    /**
     * @Route("/competences", name="competences")
     */
    public function index(): Response
    {
        return $this->render('competences/index.html.twig', [
            'controller_name' => 'CompetencesController',
        ]);
    }

    private $service;
    public function __construct(CompetenceService $service){
        $this->service=$service;
    }

    /**
     * @Route(
     *  name="put_niveau",
     *  path="api/admin/competences/{id}",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\Competences::putNiveau",
     *      "_api_collection_operation_name"="put"
     *  }
     * )
     */
    public function putNiveau($id, SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $grpe = $menager->getRepository(Competences::class)->find($id);
        if (!$grpe) {
            throw $this->createNotFoundException(
                'Pas de competences Pour l\'id '.$id
            );
        }
        $this->service->Ajouter_SuppNiveau($grpe, $serializer, $menager, $request);
        return $this->json("success",Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="add_niveau",
     *  path="/api/admin/competences",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\Controller\Competences::addNiveau",
     *      "_api_collection_operation_name"="add_niveau"
     *  }
     * )
     */
    public function addNiveau(SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $this->service->ajouterNiveau($serializer, $menager, $request);
        return $this->json("success",Response::HTTP_OK);
    }

}
