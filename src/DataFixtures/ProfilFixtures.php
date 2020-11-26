<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Profil;

class ProfilFixtures extends Fixture
{
    const tab=['Administrateur', 'CM', 'Formateur', 'Apprenant'];
    public function load(ObjectManager $manager)
    {
        for ($p=0;$p<4;$p++){
            $profil= new Profil();
            $profil->setLibelle(self::tab[$p]);
            $this->addReference(self::tab[$p],$profil);
            $manager->persist($profil);
        }

            $manager ->flush();
    }
}