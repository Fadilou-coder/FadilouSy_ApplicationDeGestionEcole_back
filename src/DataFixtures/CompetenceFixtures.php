<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Competences;
use App\Entity\GrpeCompetences;

class CompetenceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($j=0; $j<2; $j++){
            $grpecompetence = new GrpeCompetences();
            $grpecompetence ->setLibelle ("libelle".$j)
                            ->setDescriptif("descriptif".$j);
            $manager->persist($grpecompetence);

            for ($i=1; $i <= 4 ; $i++) {
                $competence = new Competences();
                $competence ->setLibelle ("libelle".$i)
                            ->addGrpeCompetence($grpecompetence);
                $manager ->persist($competence);
                
            }
        }
        
        $manager->flush();
        
    }
}