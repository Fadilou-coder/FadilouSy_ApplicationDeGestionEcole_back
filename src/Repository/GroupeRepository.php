<?php

namespace App\Repository;

use App\Entity\Groupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Groupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Groupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Groupe[]    findAll()
 * @method Groupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupe::class);
    }

    // /**
    //  * @return Groupe[] Returns an array of Groupe objects
    //  */


    public function findGroupe($id)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.apprenant', 'a')
            ->andWhere('a.id = :value')
            ->setParameter('value', $id)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGroupeByApp($id, $idgr)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.apprenant', 'a')
            ->andWhere('a.id = :value')
            ->setParameter('value', $id)
            ->andWhere('g.id= :val')
            ->setParameter('val', $idgr)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Groupe
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
