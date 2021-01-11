<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Niveaux;

class NiveauFixtures extends Fixture
{
    public const Niveau1_Reference = 'Niveaux1';
    public const Niveau2_Reference = 'Niveaux2';
    public const Niveau3_Reference = 'Niveaux3';
    public const Niveau4_Reference = 'Niveaux4';
    public function load(ObjectManager $manager)
    {
            $niv= new Niveaux;
            $niv ->setLibelle ('niveau1')
                 ->SetCritereEvaluation('critere1')
                 ->setGroupeAction('grpe_action1');
            $this->addReference(self::Niveau1_Reference, $niv);
            $manager ->persist($niv);

            $niv= new Niveaux;
            $niv ->setLibelle ('niveau2')
                 ->SetCritereEvaluation('critere2')
                 ->setGroupeAction('grpe_action2');
            $this->addReference(self::Niveau2_Reference, $niv);
            $manager ->persist($niv);

            $niv= new Niveaux;
            $niv ->setLibelle ('niveau3')
                 ->SetCritereEvaluation('critere3')
                 ->setGroupeAction('grpe_action3');
            $this->addReference(self::Niveau3_Reference, $niv);
            $manager ->persist($niv);

            $manager->flush();
    }
}