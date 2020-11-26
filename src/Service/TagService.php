<?php


namespace App\Service;


use App\Repository\ProfilRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\GroupeTag;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;

class TagService
{
    public function ajouterGrpTag(SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $grpetag = $serializer->decode($request->getContent(),"json");
        $grpeObject = new GroupeTag();
        $grpeObject ->setLibelle($grpetag["libelle"])
                    ->setDescriptif($grpetag["descriptif"]);
        foreach($grpetag["tag"] as $tag){
            if(isset($tag["id"])){
                $grpeObject->addTag($menager->getRepository(Tag::class)->find($tag["id"]));
            }
            else{
                $nouvtag =  new Tag();
                $nouvtag->setLibelle($tag["libelle"]);
                $menager->persist($nouvtag);
                $grpeObject->addTag($nouvtag);
            }
        }
        dd($grpeObject);
        $menager->persist($grpeObject);
        $menager->flush();
    }

    public function AjouterSuppTag($grptag, SerializerInterface $serializer, EntityManagerInterface $menager, Request $request)
    {
        $tag = $request->getContent();
        $tag = $serializer->decode($tag,"json");
        if(count($tag["tag"])){
            if($tag["action"]==="ajouter"){
                foreach($tag["tag"] as $tg){
                    $grptag->addTag($menager->getRepository(Tag::class)->find($tg));
                }
            }else{
                foreach($tag["tag"] as $tg){
                    $grptag->removeTag($menager->getRepository(Tag::class)->find($tg));
                }
            }
        }
        dd($grptag);
        $menager->flush();
    }
}