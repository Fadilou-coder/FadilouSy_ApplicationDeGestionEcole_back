<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Profil;
use App\Entity\User;
use App\Entity\Apprenant;

class ApprenantFixtures extends Fixture implements DependentFixtureInterface
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
            $apprenant = new Apprenant();
            $apprenant ->setProfil ($this->getReference(ProfilFixtures::APPRENANT_REFERENCE))
                  ->setPrenom($faker->firstName())
                  ->setNom($faker->lastName)
                  ->setEmail($faker->email);
            $password = $this->encoder->encodePassword ($apprenant, 'password' );
            $apprenant ->setPassword ($password );
            $manager ->persist($apprenant);
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