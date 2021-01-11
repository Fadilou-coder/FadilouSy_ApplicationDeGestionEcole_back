<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Apprenant;
use App\Entity\Competences;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class ApprenantDataPersiter implements ContextAwareDataPersisterInterface
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
        return $data instanceof Apprenant;
    }

    public function persist($data, array $context = [])
    {
        if ($context["collection_operation_name"] == "post") {
            $this->menager->persist($data);
        }
        $this->menager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        $data->setArchiver(1);
        $this->menager->flush();
        return $data;
    }
}