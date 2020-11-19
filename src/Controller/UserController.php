<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\User;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    private $encoder;
    public function  __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }

    /**
     * @Route(
     *  name="add_user",
     *  path="/api/admin/users",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\Controller\User::addUser",
     *      "_api_collection_operation_name"="add_user"
     *  }
     * )
     */
    public function addUser(SerializerInterface $serializer,Request $request, EntityManagerInterface $manager, ProfilRepository $profilRep)
    {
        $user = $request->request->all();
        $img = $request->files->get("image");
        $img = fopen($img->getRealPath(), "rb");
        $profil = $user['profils'];
        $password = $user['password'];
        $entityManager = $this->getDoctrine()->getManager();
        $userObject = $serializer->denormalize($user, User::class);
        $userObject->setImage($img);
        $userObject->setProfil($entityManager->getRepository(Profil::class)->find($profil));
        $userObject ->setPassword ($this->encoder->encodePassword ($userObject, $password));
        $manager->persist($userObject);
        $manager->flush();
        fclose($img);
        return $this->json("success",Response::HTTP_OK);

    }
}
