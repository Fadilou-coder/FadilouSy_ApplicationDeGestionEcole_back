<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ProfilsDeSortie;

class ProfilsDeSortieController extends AbstractController
{
    /**
     * @Route("/profils/de/sortie", name="profils_de_sortie")
     */
    public function index(): Response
    {
        return $this->render('profils_de_sortie/index.html.twig', [
            'controller_name' => 'ProfilsDeSortieController',
        ]);
    }

    /**
     * @Route(
     * name="delete_ps",
     * path="api/admin/profilsortie/{id}",
     * methods={"DELETE"}
     * )
     */
    public function delete($id, EntityManagerInterface $menager){
        $ps = $menager->getRepository(ProfilsDeSortie::class)->find($id);
        $ps->setArchiver(true);
        $menager->flush();
        return $this->json($ps,Response::HTTP_OK);
    }
}
