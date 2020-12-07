<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Profil;
use Container4kBcaF9\getUserControllerService;
use Doctrine\ORM\EntityManagerInterface;

final class ProfilDataPersister implements ContextAwareDataPersisterInterface
{

    private $menager;
    public function  __construct(EntityManagerInterface $menager)
    {
        $this->menager = $menager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Profil;
    }

    public function persist($data, array $context = [])
    {
        if (isset($context["collection_operation_name"])) {
            $this->menager->persist($data);
        }
       $data->setLibelle($data->getLibelle());
      $this->menager->flush();
      return $data;
    }

    public function remove($data, array $context = [])
    {
      $data->setArchiver(true);
      foreach($data->getUser() as $user){
        $user->SetArchiver(true);
      }
      $this->menager->flush();
      return $data;
    }
}