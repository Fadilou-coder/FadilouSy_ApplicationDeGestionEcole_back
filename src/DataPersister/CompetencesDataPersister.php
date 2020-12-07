<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Competences;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;

final class CompetencesDataPersister implements ContextAwareDataPersisterInterface
{

    private $menager;
    private $validate;
    public function  __construct(EntityManagerInterface $menager, ValidatorService $validate)
    {
        $this->menager = $menager;
        $this->validate = $validate;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Competences;
    }

    public function persist($data, array $context = [])
    {
        if ($context["collection_operation_name"] == "post"){
            foreach ($data->getNiveau() as $niv) {
                $this->validate->validate($niv);
            }
            $this->menager->persist($data);
        }
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