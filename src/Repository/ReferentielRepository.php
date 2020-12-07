<?php

namespace App\Repository;

use App\Entity\Referentiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Referentiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referentiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referentiel[]    findAll()
 * @method Referentiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferentielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referentiel::class);
    }

    
    public function findRefByCompetenceById($id_ref, $id_grpcompt)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.grpeCompetences', 'c')
            ->andWhere('c.id = :val')
            ->setParameter('val', $id_grpcompt)
            ->andWhere('r.id = :ref')
            ->setParameter('ref', $id_ref)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Referentiel
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
