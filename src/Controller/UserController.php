<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Admin;
use App\Entity\Apprenant;
use App\Entity\Cm;
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
    private $manager;
    public function  __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $this->encoder=$encoder;
        $this->manager=$manager;
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
    public function addUser(SerializerInterface $serializer,Request $request, ValidatorService $validate)
    {
        $user = $request->request->all();
        $img = $request->files->get("image");
        if($img){
            $img = fopen($img->getRealPath(), "rb");
        }
        if($this->manager->getRepository(Profil::class)->find($user['profils'])->getLibelle() === "APPRENANT"){
            $userObject = $serializer->denormalize($user, Apprenant::class);
        }elseif($this->manager->getRepository(Profil::class)->find($user['profils'])->getLibelle() === "FORMATEUR"){
            $userObject = $serializer->denormalize($user, Formateur::class);
        }elseif($this->manager->getRepository(Profil::class)->find($user['profils'])->getLibelle() === "Administrateur"){
            $userObject = $serializer->denormalize($user, Admin::class);
        }else{
            $userObject = $serializer->denormalize($user, Cm::class);
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

    /**
     * @Route(
     *  name="put_user",
     *  path="/api/admin/users/{id}",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\User::putUser",
     *      "_api_collection_operation_name"="put_user",
     *      "api_resource_class"=User::class
     *  }
     * )
     */
    public function putUser($id, UserService $service,Request $request)
    {
        $user = $service->getAttributes($request, 'image');
        $userUpdate = $this->manager->getRepository(User::class)->find($id);
        foreach($user as $key=>$valeur){
            $setter = 'set'.ucfirst(strtolower($key));
            if(method_exists(User::class, $setter)){
                if($key === "profil"){
                    $userUpdate->$setter($this->manager->getRepository(Profil::class)->find($valeur));
                }
                elseif($key === "password"){
                    $userUpdate->$setter($this->encoder->encodePassword ($userUpdate, $valeur));
                }else{
                    $userUpdate->$setter($valeur);
                }

                
            }
        }
        $this->manager->flush();
        return $this->json("success",Response::HTTP_OK);

    }
}
