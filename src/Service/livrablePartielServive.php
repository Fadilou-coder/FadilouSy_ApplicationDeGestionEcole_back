<?php


namespace App\Service;

use App\Entity\ApprenantLivrablePartiel;
use App\Entity\Commentaire;
use App\Entity\FilDeDiscution;
use App\Entity\LivrablePartiel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;

class livrablePartielServive
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;


    /**
     * InscriptionService constructor.
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function addDiscutionCommentaire($id,Request $request,SerializerInterface $serializer, EntityManagerInterface $manager)
    {

        $json = $serializer->decode($request->getContent(), 'json');
        $livrablepartiel = $manager->getRepository(LivrablePartiel::class)->find($id);
        if ($livrablepartiel) {
            $commentaire = new Commentaire();
            $commentaire->setDescription($json["description"]);
            $apprenantLivrablePartiel = $manager->getRepository(ApprenantLivrablePartiel::class)->findBy(["livrablePartiel" => $id]);
            foreach ($apprenantLivrablePartiel as $appLivrablePartiel) {
                if ($appLivrablePartiel->getFilDeDiscution() != NULL) {
                    $commentaire->setFilDeDiscution($apprenantLivrablePartiel[0]->getFilDeDiscution());
                } else {
                    $filDiscution = new FilDeDiscution();
                    $commentaire->setFilDeDiscution($filDiscution);
                    $apprenantLivrablePartiel[0]->setFilDiscution($filDiscution);
                    $manager->persist($filDiscution);
                }
            }
            $manager->persist($commentaire);
            $manager->flush();
            return $commentaire;
        }
    }



}