<?php

namespace App\Controller;

use App\Entity\GroupeTag;
use App\Entity\Tag;
use App\Service\TagService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

class GroupeTagController extends AbstractController
{
    /**
     * @Route("/groupe/tag", name="groupe_tag")
     */
    public function index(): Response
    {
        return $this->render('groupe_tag/index.html.twig', [
            'controller_name' => 'GroupeTagController',
        ]);
    }

    /**
     * @Route(
     *  name="put_tag",
     *  path="api/admin/grptags/{id}",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\GroupeTag::putTag",
     *      "_api_collection_operation_name"="put"
     *  }
     * )
     */
    public function putTag($id, SerializerInterface $serializer, EntityManagerInterface $menager, Request $request, TagService $service)
    {
        $grptag = $menager->getRepository(GroupeTag::class)->find($id);
        if (!$grptag) {
            throw $this->createNotFoundException(
                'Pas de groupe tag Pour l\'id '.$id
            );
        }
        $service->AjouterSuppTag($grptag, $serializer, $menager, $request);
        return $this->json("success",Response::HTTP_OK);
    }

     /**
     * @Route(
     *  name="add_tag",
     *  path="/api/admin/grptags",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\Controller\GroupeTag::addgrptags",
     *      "_api_collection_operation_name"="add_tag"
     *  }
     * )
     */
    public function addgrptags(SerializerInterface $serializer, EntityManagerInterface $menager, Request $request, TagService $service)
    {
        $service->ajouterGrpTag($serializer, $menager, $request);
        return $this->json("success",Response::HTTP_OK);
    }
}
