<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Competences;
use Container4kBcaF9\getUserControllerService;
use Doctrine\ORM\EntityManagerInterface;

final class CompetencesDataPersister implements ContextAwareDataPersisterInterface
{

    private $menager;
    public function  __construct(EntityManagerInterface $menager)
    {
        $this->menager = $menager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Competences;
    }

    public function persist($data, array $context = [])
    {
      $this->menager->persist($data);
      $this->menager->flush();
      return $data;
    }

    public function remove($data, array $context = [])
    {
      $data->setArchiver(1);
      foreach($data->getNiveaux() as $niv){
        $niv->SetArchiver(true);
      }
      $this->menager->flush();
      return $data;
    }
}