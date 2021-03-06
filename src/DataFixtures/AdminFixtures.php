<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Profil;
use App\Entity\User;

class AdminFixtures extends Fixture implements DependentFixtureInterface
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
            $user = new Admin();
            $user ->setProfil ($this->getReference(ProfilFixtures::ADMIN_REFERENCE))
                  ->setPrenom($faker->firstName())
                  ->setNom($faker->lastName)
                  ->setEmail($faker->email);
            $password = $this->encoder->encodePassword ($user, 'password' );
            $user ->setPassword ($password );
            $manager ->persist($user);
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