<?php

namespace App\DataFixtures;

use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Groupe;
use App\Entity\Promo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PromoFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;
    public function  __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
            $promo = new Promo();
            $promo  ->setLangue("Français")
                    ->setDescription("description")
                    ->setDataFinReelle($faker->dateTime)
                    ->setDateDebut($faker->dateTime)
                    ->setDateFinProvisoire(($faker->dateTime))
                    ->setEtat("en cours")
                    ->setFabrique("fabrique")
                    ->setReferenceAgate("referentiel_Agate")
                    ->setTitre("Promo1")
                    ->setTitre("titre promo")
                    ->setLieu("Dakar");

            for ($i=0; $i < 4 ; $i++) {
                $groupe = new Groupe();
                $groupe ->setNom("nom".$i)
                        ->setType("type", $i)
                        ->setStatus("en cours")
                        ->setPromo($promo);
                for($j=0; $j < 4; $j++){
                    $apprenant = new Apprenant();
                    $apprenant ->setProfil ($this->getReference(ProfilFixtures::APPRENANT_REFERENCE))
                        ->setPrenom($faker->firstName())
                        ->setNom($faker->lastName)
                        ->setEmail($faker->email);
                    $password = $this->encoder->encodePassword ($apprenant, 'password' );
                    $apprenant ->setPassword ($password );
                    $manager ->persist($apprenant);
                    $groupe->addApprenant($apprenant);
                }
                for ($k=0; $k <4 ; $k++) {
                    $formateur = new Formateur();
                    $formateur ->setProfil ($this->getReference(ProfilFixtures::FORMATEUR_REFERENCE))
                        ->setPrenom($faker->firstName())
                        ->setNom($faker->lastName)
                        ->setEmail($faker->email);
                    $password = $this->encoder->encodePassword ($formateur, 'password' );
                    $formateur ->setPassword ($password );
                    $manager ->persist($formateur);
                    $groupe->addFormateur($formateur);
                }
                $manager ->persist($groupe);
            }
            $manager->flush();

        

    }
    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}