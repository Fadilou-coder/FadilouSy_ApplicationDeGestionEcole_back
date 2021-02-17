<?php

namespace App\Controller;

use App\Entity\Competences;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\GrpeCompetences;
use App\Entity\Niveaux;
use App\Service\ValidatorService;

class GrpCmptController extends AbstractController
{
    /**
     * @Route("/grp/cmpt", name="grp_cmpt")
     */
    public function index(): Response
    {
        return $this->render('grp_cmpt/index.html.twig', [
            'controller_name' => 'GrpCmptController',
        ]);
    }

    /**
     * @Route(
     *  name="delGrpCompt",
     *  path="api/admin/grpecompetences/{id}",
     *  methods={"DELETE"},
     * )
     * @param $id
     * @param EntityManagerInterface $menager
     * @return JsonResponse
     */
    public function delGrpCompt($id, EntityManagerInterface $menager)
    {
        $grpcomt = $menager->getRepository(GrpeCompetences::class)->find($id);
        $grpcomt->setArchiver(true);
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="addCompt",
     *  path="api/admin/competences",
     *  methods={"POST"},
     * )
     * @param $id
     * @param EntityManagerInterface $menager
     * @return JsonResponse
     */

    public function addCompetence(EntityManagerInterface $menager, Request $request, serializerInterface $serializer, ValidatorService $validate){
        $compt = $serializer->decode($request->getContent(), 'json');
        $nouvCompt = new Competences();
        $nouvCompt->setLibelle($compt['libelle']);
        if (isset($compt['grpeCompetences'])) {
            foreach ($compt['grpeCompetences'] as $value) {
                $grpcomt = $menager->getRepository(grpeCompetences::class)->findOneBy(['libelle' => $value['libelle']]);
                if ($grpcomt) {
                    $nouvCompt->addGrpeCompetence($grpcomt);
                }
            }
        }
        
        foreach ($compt['niveaux'] as  $value) {
            $niveau = $serializer->denormalize($value, Niveaux::class);
            $nouvCompt->addNiveau($niveau);
        }
        $validate->validate($nouvCompt);
        $menager->persist($nouvCompt);
        $menager->flush();
        return $this->json($nouvCompt, Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="addGrpCompt",
     *  path="api/admin/grpecompetences",
     *  methods={"POST"},
     * )
     * @param $id
     * @param EntityManagerInterface $menager
     * @return JsonResponse
     */

    public function addGrpCompetence(EntityManagerInterface $menager, Request $request, serializerInterface $serializer, ValidatorService $validate){
        $grpCompt = $serializer->decode($request->getContent(), 'json');
        $nouvGrpCompt = new GrpeCompetences();
        $nouvGrpCompt ->setLibelle($grpCompt["libelle"])
                      ->setDescriptif($grpCompt["descriptif"]);
        foreach($grpCompt['competences'] as $compt){
          if ($menager->getRepository(Competences::class)->findOneBy(['libelle' => $compt['libelle']])) {
            $nouvGrpCompt->addCompetence($menager->getRepository(Competences::class)->findOneBy(['libelle' => $compt['libelle']]));
          }
        }
        $validate->validate($nouvGrpCompt);      
        $menager->persist($nouvGrpCompt);
        $menager->flush();
        return $this->json($nouvGrpCompt, Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="editGrpCompt",
     *  path="api/admin/grpecompetences/{id}",
     *  methods={"PUT"},
     * )
     * @param $id
     * @param EntityManagerInterface $menager
     * @return JsonResponse
     */

    public function editGrpCompetence(EntityManagerInterface $menager, Request $request, serializerInterface $serializer){
        $data = $serializer->decode($request->getContent(), 'json');
        $grpCompt = $menager->getRepository(GrpeCompetences::class)->find($data["id"]);
        $grpCompt ->setLibelle($data["libelle"])
                      ->setDescriptif($data["descriptif"]);
        foreach($grpCompt->getCompetences() as $c){
          $grpCompt->removeCompetence($c);
        }
        foreach($data['competences'] as $compt){
          if ($menager->getRepository(Competences::class)->findOneBy(['libelle' => $compt['libelle']])) {
            $grpCompt->addCompetence($menager->getRepository(Competences::class)->findOneBy(['libelle' => $compt['libelle']]));
          }
        }
        $menager->flush();
        return $this->json($grpCompt, Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="putCompt",
     *  path="api/admin/competences/{id}",
     *  methods={"PUT"},
     * )
     */

    public function editCompetence(EntityManagerInterface $menager, Request $request, serializerInterface $serializer){
        $data = $serializer->decode($request->getContent(), 'json');
        $compt = $menager->getRepository(Competences::class)->find($data["id"]);
        $compt ->setLibelle($data["libelle"]);
        foreach($compt->getGrpeCompetences() as $c){
          $compt->removeGrpeCompetence($c);
        }
        $i = 0;
        foreach($compt->getNiveau() as $n){
            $n->setCritereEvaluation($data['niveaux'][$i]['critereEvaluation']);
            $n->setGroupeAction($data['niveaux'][$i]['groupeAction']);
            $i++;
        }
        foreach($data['grpeCompetences'] as $grpCompt){
          if ($menager->getRepository(GrpeCompetences::class)->findOneBy(['libelle' => $grpCompt['libelle']])) {
            $compt->addGrpeCompetence($menager->getRepository(GrpeCompetences::class)->findOneBy(['libelle' => $grpCompt['libelle']]));
          }
        }
        //dd($compt);
        $menager->flush();
        return $this->json($compt, Response::HTTP_OK);
    }
}
