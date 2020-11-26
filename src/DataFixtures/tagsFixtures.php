<?php

namespace App\DataFixtures;

use App\Entity\GroupeTag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Tag;

class tagsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($j=0; $j<4; $j++){
            $grpetag = new GroupeTag();
            $grpetag->setLibelle("libelle".$j)
                    ->setDescriptif("Descriptif".$j);
            $manager ->persist($grpetag);
            for ($i=1; $i <= 4 ; $i++) {
                $tag = new Tag();
                $tag ->setLibelle ("libelle".$i);
                $tag->addGroupeTag($grpetag);
                $manager ->persist($tag);
            }
        }

        $manager->flush();
    }
}