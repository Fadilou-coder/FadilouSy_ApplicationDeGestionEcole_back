<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Profil;

class ProfilFixtures extends Fixture
{
    public const ADMIN_REFERENCE = 'ADMIN';
    public const CM_REFERENCE = 'CM';
    public const FORMATEUR_REFERENCE = 'FORMATEUR';
    public const APPRENANT_REFERENCE = 'APPRENANT';

    public function load(ObjectManager $manager)
    {
            $profil= new Profil();
            $profil->setLibelle('ADMIN');
            $this->addReference(self::ADMIN_REFERENCE,$profil);
            $manager->persist($profil);

            $manager ->flush();

            $profil= new Profil();
            $profil->setLibelle('CM');
            $this->addReference(self::CM_REFERENCE,$profil);
            $manager->persist($profil);

            $profil= new Profil();
            $profil->setLibelle('FORMATEUR');
            $this->addReference(self::FORMATEUR_REFERENCE,$profil);
            $manager->persist($profil);

            $profil= new Profil();
            $profil->setLibelle('APPRENANT');
            $this->addReference(self::APPRENANT_REFERENCE,$profil);
            $manager->persist($profil);

            $manager ->flush();

            $manager ->flush();

            $manager ->flush();
    }
}