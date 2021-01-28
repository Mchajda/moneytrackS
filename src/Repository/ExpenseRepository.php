<?php

namespace App\Repository;

use App\Entity\Expense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Expense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expense[]    findAll()
 * @method Expense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    /**
     * @param $year
     * @param $month
     * @return Expense[] Returns an array of Expense objects
     */

    public function getForDate($user_id, $year, $month)
    {
        $numOfDays = date("t", mktime(0,0,0,$month,3,$year));
        $from = $year.'-'.$month.'-01';
        $to = $year.'-'.$month.'-'.$numOfDays;

        return $this->createQueryBuilder('e')
            ->andWhere('e.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->andWhere('e.date BETWEEN :from AND :to')
            ->setParameter('from', $from )
            ->setParameter('to', $to)
            ->orderBy('e.date', 'DESC')
            ->orderBy('e.created_at', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $user_id
     * @param $num
     * @return Expense[] Returns an array of Expense objects
     */

    public function getLast($user_id, $num)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->orderBy('e.date', 'DESC')
            ->orderBy('e.created_at', 'DESC')
            ->setMaxResults($num)
            ->getQuery()
            ->getResult()
            ;
    }


    // /**
    //  * @return Expense[] Returns an array of Expense objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Expense
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}