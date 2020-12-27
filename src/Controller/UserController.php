<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Admin;
use App\Entity\Apprenant;
use App\Entity\ApprenantLivrablePartiel;
use App\Entity\Cm;
use App\Entity\Formateur;
use App\Entity\LivrablePartiel;
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
        if($user['profils'] === "APPRENANT"){
            $userObject = $serializer->denormalize($user, Apprenant::class);
        }elseif( $user['profils'] === "FORMATEUR"){
            $userObject = $serializer->denormalize($user, Formateur::class);
        }elseif($user['profils'] === "ADMIN"){
            $userObject = $serializer->denormalize($user, Admin::class);
        }else{
            $userObject = $serializer->denormalize($user, Cm::class);
        }
        $userObject->setImage($img);
        $userObject->setProfil($this->manager->getRepository(Profil::class)->findOneBy(['libelle' => $user['profils']]));
        $userObject ->setPassword ($this->encoder->encodePassword ($userObject, $user['password']));
        $validate->validate($userObject);
        $this->manager->persist($userObject);
        $this->manager->flush();
        return $this->json($userObject,Response::HTTP_OK);
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
     * @param $id
     * @param UserService $service
     * @param Request $request
     * @return JsonResponse
     */
    public function putUser($id, UserService $service,Request $request)
    {
        $user = $service->getAttributes($request);
        $userUpdate = $this->manager->getRepository(User::class)->find($id);
        foreach($user as $key=>$valeur){
            $setter = 'set'.ucfirst(strtolower($key));
            if(method_exists(User::class, $setter)){
                if($key === "profil"){
                    $userUpdate->$setter($this->manager->getRepository(Profil::class)->findOneBy(['libelle' => $valeur]));
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

    /**
     * @Route(
     *  name="put_status",
     *  path="api/apprenants/{id}/livrablepartiels/{iD}",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\User::putStatus",
     *      "_api_item_operation_name"="put"
     *  }
     * )
     * @param $id
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $menager
     * @param Request $request
     * @return JsonResponse
     */
    public function putStatus($id, $iD, SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $lP = $menager->getRepository(LivrablePartiel::class)->find($iD);
        $apprenant = $menager->getRepository(Apprenant::class)->find($id);
        $postman = $serializer->decode($request->getContent(), 'json');
        if (!$apprenant || !$lP) {
            throw $this->createNotFoundException(
                'l\'apprenant ou le groupe n\'existe pas'
            );
        }
        $appLp = $menager->getRepository(ApprenantLivrablePartiel::class)->findOneBy(["apprenant"=>$id, "livrablePartiel"=>$iD]);
        $appLp->setEtat($postman["etat"]);
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="delProfil",
     *  path="api/admin/profils/{id}",
     *  methods={"DELETE"},
     *  defaults={
     *      "_controller"="\app\Controller\User::delProfil",
     *      "_api_item_operation_name"="delete"
     *  }
     * )
     * @param $id
     * @param EntityManagerInterface $menager
     * @return JsonResponse
     */
    public function delProfil($id, EntityManagerInterface $menager)
    {
        $profil = $menager->getRepository(Profil::class)->find($id);
        $profil->setArchiver(true);
        foreach($profil->getUser() as $user){
            $user->SetArchiver(true);
        }
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="delUser",
     *  path="api/admin/users/{id}",
     *  methods={"DELETE"},
     *  defaults={
     *      "_controller"="\app\Controller\User::delUser",
     *      "_api_item_operation_name"="delete"
     *  }
     * )
     * @param $id
     * @param EntityManagerInterface $menager
     * @return JsonResponse
     */
    public function delUser($id, EntityManagerInterface $menager)
    {
        $user = $menager->getRepository(User::class)->find($id);
        $user->setArchiver(true);
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="putProfil",
     *  path="api/admin/profils/{id}",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\User::putProfil",
     *      "_api_item_operation_name"="put"
     *  }
     * )
     * @param $id
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $menager
     * @param Request $request
     * @return JsonResponse
     */
    public function putProfil($id, EntityManagerInterface $menager, SerializerInterface $serializer, Request $request)
    {
        $profil = $menager->getRepository(Profil::class)->find($id);
        $postman = $serializer->decode($request->getContent(), 'json');
        $profil->setLibelle($postman["libelle"]);
        $menager->flush();
        return $this->json($profil,Response::HTTP_OK);
    }
}
