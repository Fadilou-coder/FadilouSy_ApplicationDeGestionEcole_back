<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\GrpeCompetences;

class grpeCompetencesFixtures extends Fixture
{
    public const Groupe1_Reference = 'GrpeCompetences1';
    public const Groupe2_Reference = 'GrpeCompetences2';
    public const Groupe3_Reference = 'GrpeCompetences3';
    public const Groupe4_Reference = 'GrpeCompetences4';
    public function load(ObjectManager $manager)
    {
            // $groupe = new GrpeCompetences() ;
            // $groupe ->setLibelle ('Group1');
            // $groupe ->setDescriptif('descriptif1');
            // $this->addReference(self::Groupe1_Reference, $groupe);
            // $manager ->persist($groupe);

            // $groupe1 = new GrpeCompetences() ;
            // $groupe1 ->setLibelle ('Group2');
            // $groupe1 ->setDescriptif('descriptif2');
            // $this->addReference(self::Groupe2_Reference, $groupe);
            // $manager ->persist($groupe1);

            // $groupe2 = new GrpeCompetences() ;
            // $groupe2 ->setLibelle ('Group3');
            // $groupe2 ->setDescriptif('descriptif3');
            // $this->addReference(self::Groupe3_Reference, $groupe);
            // $manager ->persist($groupe2);

            // $groupe3 = new GrpeCompetences() ;
            // $groupe3 ->setLibelle ('Group4');
            // $groupe3 ->setDescriptif('descriptif4');
            // $this->addReference(self::Groupe4_Reference, $groupe3);
            // $manager ->persist($groupe);

            // $manager->flush();
    }
}