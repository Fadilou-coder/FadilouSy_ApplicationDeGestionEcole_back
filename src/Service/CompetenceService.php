<?php


namespace App\Service;

use App\Entity\Competences;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Niveaux;
use Doctrine\ORM\EntityManagerInterface;

class CompetenceService
{
    public function Ajouter_SuppNiveau($grpe, SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $grpecompt = $request->getContent();
        $grpecompt = $serializer->decode($grpecompt,"json");
        if(count($grpecompt["niveaux"])){
            if($grpecompt["action"]==="ajouter"){
                foreach($grpecompt["niveaux"] as $niv){
                    $grpe->addNiveaux($menager->getRepository(Niveaux::class)->find($niv));
                }
            }else{
                foreach($grpecompt["niveaux"] as $niv){
                    $grpe->removeNiveaux($menager->getRepository(Niveaux::class)->find($niv));
                }
            }
        }
        $menager->flush();
    }

    public function ajouterNiveau(SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $compt = $serializer->decode($request->getContent(),"json");
        $comptObject = new Competences();
        $comptObject ->setLibelle($compt["libelle"]);
        foreach($compt["niveaux"] as $niv){
            if(isset($niv["id"])){
                $comptObject->addNiveaux($menager->getRepository(Niveaux::class)->find($niv["id"]));
            }
            else{
                $niveau =  new Niveaux();
                $niveau->setLibelle($niv["libelle"])
                        ->setCritereEvaluation($niv["critereEvaluation"])
                        ->setGroupeAction($niv["groupeAction"]);
                $menager->persist($niveau);
                $comptObject->addNiveaux($niveau);
            }
        }
        $menager->persist($comptObject);
        $menager->flush();
    }
}