<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;

final class ProfilDataPersister implements ContextAwareDataPersisterInterface
{

    private $menager;
    private $decorated;
    public function  __construct(EntityManagerInterface $menager, DataPersisterInterface $decorated)
    {
        $this->menager = $menager;
        $this->decorated = $decorated;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Profil;
    }

    public function persist($data, array $context = [])
    {
        $this->decorated->persist($data);
      return $data;
    }

    public function remove($data, array $context = [])
    {
        dd($data);
      $data->setArchiver(true);
      foreach($data->getUser() as $user){
        $user->SetArchiver(true);
      }
      $this->menager->flush();
      return $data;
    }
}