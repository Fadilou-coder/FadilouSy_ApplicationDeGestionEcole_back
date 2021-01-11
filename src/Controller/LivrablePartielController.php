<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\ApprenantLivrablePartiel;
use App\Entity\Brief;
use App\Entity\BriefMaPromo;
use App\Entity\Commentaire;
use App\Entity\CompetencesValides;
use App\Entity\FilDeDiscution;
use App\Entity\Groupe;
use App\Entity\LivrablePartiel;
use App\Entity\Promo;
use App\Entity\Referentiel;
use App\Repository\ApprenantLivrablePartielRepository;
use App\Repository\ApprenantRepository;
use App\Service\livrablePartielServive;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class LivrablePartielController extends AbstractController
{
    /**
     * @Route("/livrable/partiel", name="livrable_partiel")
     */
    public function index(): Response
    {
        return $this->render('livrable_partiel/index.html.twig', [
            'controller_name' => 'LivrablePartielController',
        ]);
    }

    /**
     * @Route(
     * name="form_stat",
     * path="api/formateurs/promo/{idp}/referentiel/{idr}/statistiques/competences",
     * methods={"GET"}
     * )
     */

    public function getCompetences(SerializerInterface $serializer,$idp,$idr, EntityManagerInterface $manager)
    {

        $promo = $manager->getRepository(Promo::class)->find($idp);
        $ref = $manager->getRepository(Referentiel::class)->find($idr);
        if (!$promo || !$ref) {
            throw $this->createNotFoundException(
                'la promo ou le referentiel n\'existe pas'
            );
        }
        if (!$promo->getReferentiel() == $ref) {
            throw $this->createNotFoundException(
                'la promo dont l\'id = ' . $idp . ' n\'est pas dans le referentiel dont l\'id = ' . $idr
            );
        }
        foreach ($promo->getReferentiel()->getGrpeCompetences() as $grpc) {
            foreach ($grpc->getCompetences() as $competence) {
                $nb1 = 0;
                $nb2 = 0;
                $nb3 = 0;
                foreach ($competence->getCompetencesValides() as $compValid) {
                    if ($compValid->getNiveau1() == true) {
                        $nb1 += 1;
                    }
                    if ($compValid->getNiveau2() == true) {
                        $nb2 += 1;
                    }
                    if ($compValid->getNiveau3() == true) {
                        $nb3 += 1;
                    }
                }
                $tab[] = ["competence" => $competence, "niveau 1" => $nb1 . ' apprenant valide', "niveau 2" => $nb2 . ' apprenant valide', "niveau 3" => $nb3 . ' apprenant valide'];


            }

            return $this->json($tab, 200, [], ["groups" => "cmpt:read"]);
        }
    }

    /**
     * @Route(
     * name="brief_stat",
     * path="api/apprenants/{idap}/promo/{idp}/referentiel/{idr}/statistiques/briefs",
     * methods={"GET"},
     * )
     */

    public function getnbrBrief($idp,$idr, $idap, EntityManagerInterface $manager)
    {
        $promo = $manager->getRepository(Promo::class)->find($idp);
        $ref = $manager->getRepository(Referentiel::class)->find($idr);
        $app = $manager->getRepository(Apprenant::class)->find($idap);
        if (!$promo || !$ref || !$app){
            throw $this->createNotFoundException(
                'la promo ou le referentiel ou l`apprenant n\'existe pas'
            );
        }
        if (!($promo->getReferentiel() == $ref) && !($app->getPromo() == $promo)){
            throw $this->createNotFoundException(
                'la promo dont l\'id = '.$idp.' n\'est pas dans le referentiel dont l\'id = '.$idr.'l\'apprenant n`est pas dans le promo'
            );
        }
        $nbrValide = 0; $nbrRendu = 0; $nbrAssigne = 0; $nbrNonValide = 0;
            foreach ($app->getBriefApprenant() as $brapp){
                if($brapp->getEtat() === 'valide'){
                    $nbrValide++;
                }elseif ($brapp->getEtat() === 'assigne'){
                    $nbrAssigne++;
                }elseif ($brapp->getEtat() === 'rendu'){
                    $nbrRendu++;
                }else{
                    $nbrNonValide++;
                }
            }
            $tab = ["Apprenant"=>$app, "NbrBriefAssigner"=>$nbrAssigne, "NbrBriefRendu"=>$nbrRendu, "NbrBriefValide"=>$nbrValide, "NbrBriefNonValide"=>$nbrNonValide];
        return $this->json($tab,200,[],["groups"=>"grp"]);
    }

    /**
     * @Route(
     * name="add_supp_liv",
     * path="api/formateurs/promo/{id}/brief/{iD}/livrablepartiels",
     * methods={"PUT"},
     * )
     */
    public function Add_Supp_Liv($id,$iD,EntityManagerInterface $manager, Request $request, SerializerInterface $serializer)
    {
        $promo = $manager->getRepository(Promo::class)->find($id);
        $br = $manager->getRepository(Brief::class)->find($iD);
        $request = $serializer->decode($request->getContent(), 'json');
        if (!$promo || !$br ){
            throw $this->createNotFoundException(
                'la promo ou le brief n\'existe pas'
            );
        }
        $brmapr = $manager->getRepository(BriefMaPromo::class)->findOneBy(["promo"=>$id, "brief"=>$iD]);
        if ($brmapr){
            foreach ($request["livrablepartiels"] as $livr) {
                if ($manager->getRepository(LivrablePartiel::class)->find($livr["id"])) {
                    if ($request["action"] === "add") {
                        $brmapr->addLivrablePartiel($manager->getRepository(LivrablePartiel::class)->find($livr["id"]));
                    } else {
                        $brmapr->removeLivrablePartiel($manager->getRepository(LivrablePartiel::class)->find($livr["id"]));
                    }
                }
            }
        }
        $manager->flush();
        return $this->json("success");
    }

    /**
     * @Route(
     * name="for_add_supp_liv",
     * path="api/formateurs/livrablepartiels/{id}/commentaires",
     * methods={"POST"},
     * )
     */
    public function addDiscutionCommentaire($id,Request $request,SerializerInterface $serializer, EntityManagerInterface $manager, livrablePartielServive $livrablePartielServive)
    {
        return $this->json($livrablePartielServive->addDiscutionCommentaire($id, $request, $serializer, $manager));
    }

    /**
     * @Route(
     * name="app_add_supp_liv",
     * path="api/apprenants/livrablepartiels/{id}/commentaires",
     * methods={"POST"},
     * )
     */
    public function addDiscutionCommentaireApp($id,Request $request,SerializerInterface $serializer, EntityManagerInterface $manager, livrablePartielServive $livrablePartielServive)
    {
            return $this->json($livrablePartielServive->addDiscutionCommentaire($id, $request, $serializer, $manager));
    }
}
