<?php

namespace App\Controller;

use App\Entity\GrpeCompetences;
use App\Entity\Referentiel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

class ReferentielController extends AbstractController
{
    /**
     * @Route(
     *  name="delRef",
     *  path="api/admin/referentiels/{id}",
     *  methods={"DELETE"},
     * )
     * @param $id
     * @param EntityManagerInterface $menager
     * @return JsonResponse
     */
    public function delRef($id, EntityManagerInterface $menager)
    {
        $grpcomt = $menager->getRepository(Referentiel::class)->find($id);
        $grpcomt->setArchiver(true);
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="addRef",
     *  path="/api/admin/referentiels",
     *  methods={"POST"},
     * )
     * @param $id
     * @param EntityManagerInterface $menager
     * @return JsonResponse
     */
    public function addRef(EntityManagerInterface $menager, Request $request, SerializerInterface $serializer)
    {
       
        $ref = $request->request->all();
        $pr = $request->files->get("programme");
        $pr = fopen($pr->getRealPath(), "rb");
        $nouvRef = $serializer->denormalize($ref, Referentiel::class);
        $grpeCompetences = explode(',', $ref['grpCmpt']);
        //$grpeCompetences = explode(',', $ref['grpCmpt']);
        foreach($ref['grpCmpt'] as $grpComp){
            $nouvRef->addGrpeCompetence($menager->getRepository(GrpeCompetences::class)->findOneBy(['libelle' => $grpComp['libelle']]));
        }
        $nouvRef->setProgramme($pr);
        $menager->persist($nouvRef);
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }


     /**
     * @Route(
     *  name="put_ref",
     *  path="/api/admin/referentiels/{id}",
     *  methods={"PUT"},
     * )
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function putUser($id, Request $request, EntityManagerInterface $menager)
    {
        $donnee = $request->getContent();
        $ref = [];
        //eclater la chaine
        $data = preg_split("/form-data; /", $donnee);
        //suppression du premier élément
        unset($data[0]);
        foreach ($data as $item){
            $data2 = preg_split("/\r\n/", $item);
            array_pop($data2);
            array_pop($data2);
            $key = explode('"', $data2[0]);
            //dd($key);
            $key = $key[1];
            $ref[$key] = end($data2);
        }

        $refUpdate = $menager->getRepository(Referentiel::class)->find($id);
        foreach($ref as $key=>$valeur){
            $setter = 'set'.ucfirst(strtolower($key));
            if(method_exists(Referentiel::class, $setter)){
                if($key === "grpCmpt"){
                    foreach($refUpdate->getGrpeCompetences() as $grp){
                        $refUpdate->removeGrpeCompetence($grp);
                    }
                    //$grpeCompetences = explode(',', $ref['grpCmpt']);
                    foreach($ref['grpCmpt'] as $grpComp){
                        $refUpdate->addGrpeCompetence($menager->getRepository(GrpeCompetences::class)->findOneBy(['libelle' => $grpComp['libelle']]));
                    }
                }else{
                    $refUpdate->$setter($valeur);
                }
            }
        }
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);

    }
}