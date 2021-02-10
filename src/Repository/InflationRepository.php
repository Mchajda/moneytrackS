<?php

namespace App\Repository;

use App\Entity\Inflation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Inflation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inflation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inflation[]    findAll()
 * @method Inflation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InflationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inflation::class);
    }

    // /**
    //  * @return Inflation[] Returns an array of Inflation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Inflation
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
