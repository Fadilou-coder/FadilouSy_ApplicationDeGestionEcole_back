<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Referentiel;

class referentielFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

            for ($i=1; $i <= 4 ; $i++) {
                $ref = new Referentiel();
                $ref ->setLibelle ("libelle".$i)
                     ->setPresentation("presentation".$i)
                     ->setCritereEvaluation("critere d'evaluation".$i)
                     ->setCritereAdmission("CritereAdmission".$i)
                     ->setProgramme("Programme".$i);
                $manager ->persist($ref);
                
            }
        $manager->flush();
        
    }
}