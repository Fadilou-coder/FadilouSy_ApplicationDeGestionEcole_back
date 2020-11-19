<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Competences;

class CompetenceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i=1; $i <=4 ; $i++) {
            $competence = new Competences();
            $competence ->setLibelle ("libelle".$i);
            $manager ->persist($competence);
        }
        $manager->flush();
    }
}