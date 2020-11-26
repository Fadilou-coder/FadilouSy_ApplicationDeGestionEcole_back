<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Profil;
use App\Entity\User;
use App\Service\UserService;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantController extends AbstractController
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
    private $manager;
    public function  __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $this->encoder=$encoder;
        $this->manager=$manager;
    }

    /**
     * @Route(
     *  name="add_app",
     *  path="/api/apprenant",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\Controller\Apprenant::addApp",
     *      "_api_collection_operation_name"="add_app"
     *  }
     * )
     */
    public function addApp(SerializerInterface $serializer,Request $request, ValidatorService $validate)
    {
        $user = $request->request->all();
        $img = $request->files->get("image");
        if($img){
            $img = fopen($img->getRealPath(), "rb");
        }
        if($this->manager->getRepository(Profil::class)->find($user['profils'])->getLibelle() === "APPRENANT"){
            $userObject = $serializer->denormalize($user, Apprenant::class);
        }
        else{
            return $this->json("le profil doit etre celui d'un apprenant",Response::HTTP_OK);
        }
        $userObject->setImage($img);
        $userObject->setProfil($this->manager->getRepository(Profil::class)->find($user['profils']));
        $userObject ->setPassword ($this->encoder->encodePassword ($userObject, $user['password']));

        $validate->validate($userObject);
        $this->manager->persist($userObject);
        $this->manager->flush();
        fclose($img);
        return $this->json("success",Response::HTTP_OK);

    }
}