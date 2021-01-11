<?php

namespace App\Repository;

use App\Entity\GrpeCompetences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GrpeCompetences|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrpeCompetences|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrpeCompetences[]    findAll()
 * @method GrpeCompetences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrpeCompetencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GrpeCompetences::class);
    }

    // /**
    //  * @return GrpeCompetences[] Returns an array of GrpeCompetences objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GrpeCompetences
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
