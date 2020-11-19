<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Profil;

class ProfilFixtures extends Fixture
{
    public const ADMIN_REFERENCE = 'Administrateur';
    public const CM_REFERENCE = 'CM';
    public const FORMATEUR_REFERENCE = 'Formateur';
    public const APPRENANT_REFERENCE = 'Apprenant';
    public function load(ObjectManager $manager)
    {
            $admin = new Profil() ;
            $admin ->setLibelle ('Administrateur');
            $this->addReference(self::ADMIN_REFERENCE, $admin);
            $manager ->persist($admin);

            $CM = new Profil() ;
            $CM ->setLibelle ('CM');
            $this->addReference(self::CM_REFERENCE, $CM);
            $manager ->persist($CM);   
            
            $FORMATEUR = new Profil() ;
            $FORMATEUR ->setLibelle ('FORMATEUR');
            $this->addReference(self::FORMATEUR_REFERENCE, $FORMATEUR);
            $manager ->persist($FORMATEUR);  

            $APPRENANT = new Profil() ;
            $APPRENANT ->setLibelle ('APPRENANT');
            $this->addReference(self::APPRENANT_REFERENCE, $APPRENANT);
            $manager ->persist($APPRENANT);

            $manager ->flush();
    }
}