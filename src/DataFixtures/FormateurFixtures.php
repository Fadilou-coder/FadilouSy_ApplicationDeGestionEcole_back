<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Profil;
use App\Entity\User;
use App\Entity\Formateur;

class FormateurFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;
    public function  __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i=1; $i <=4 ; $i++) {
            $formateur = new Formateur();
            $formateur ->setProfil ($this->getReference(ProfilFixtures::FORMATEUR_REFERENCE))
                  ->setPrenom($faker->firstName())
                  ->setNom($faker->lastName)
                  ->setEmail($faker->email);
            $password = $this->encoder->encodePassword ($formateur, 'password' );
            $formateur ->setPassword ($password );
            $manager ->persist($formateur);
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