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

    //new function

    /**
     * @param $user_id
     * @param $year
     * @param $month
     * @param bool $amIPayer
     * @return Expense[] Returns an array of Expense objects
     */
    public function getExpensesForMonth($user_id, $year, $month, bool $amIPayer = true): array
    {
        $numOfDays = date("t", mktime(0, 0, 0, $month, 3, $year));
        $from = $year . '-' . $month . '-01';
        $to = $year . '-' . $month . '-' . $numOfDays;

        return $this->createQueryBuilder('e')
            ->andWhere('e.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->andWhere('e.amIPayer = :amIPayer')
            ->setParameter('amIPayer', $amIPayer)
            ->andWhere('e.date BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->andWhere('e.category != 25')
            ->orderBy('e.created_at', 'DESC')
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //new function

    /**
     * @param $user_id
     * @param $year
     * @param $month
     * @return Expense[] Returns an array of Expense objects
     */
    public function getIncomesForMonth($user_id, $year, $month): array
    {
        $numOfDays = date("t", mktime(0, 0, 0, $month, 3, $year));
        $from = $year . '-' . $month . '-01';
        $to = $year . '-' . $month . '-' . $numOfDays;

        return $this->createQueryBuilder('e')
            ->andWhere('e.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->andWhere('e.date BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->andWhere('e.category = 25')
            ->orderBy('e.created_at', 'DESC')
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $user_id
     * @param $year
     * @param $month
     * @param $direction
     * @param bool $amIPayer
     * @return Expense[] Returns an array of Expense objects
     */
    public function getForDate($user_id, $year, $month, $direction, bool $amIPayer = true): array
    {
        $numOfDays = date("t", mktime(0, 0, 0, $month, 3, $year));
        $from = $year . '-' . $month . '-01';
        $to = $year . '-' . $month . '-' . $numOfDays;

        return $this->createQueryBuilder('e')
            ->andWhere('e.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->andWhere('e.category_id = :direction')
            ->setParameter('direction', $direction)
            ->andWhere('e.amIPayer = :amIPayer')
            ->setParameter('amIPayer', $amIPayer)
            ->andWhere('e.date BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('e.created_at', 'DESC')
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $user_id
     * @param $year
     * @param $amIPayer
     * @return Expense[] Returns an array of Expense objects
     */
    public function getExpensesForYearByUserId($user_id, $year, $amIPayer): array
    {
        $from = $year . '-01-01';
        $to = $year . '-12-31';

        return $this->createQueryBuilder('e')
            ->andWhere('e.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->andWhere('e.category != 25')
            ->andWhere('e.amIPayer = :amIPayer')
            ->setParameter('amIPayer', $amIPayer)
            ->andWhere('e.date BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('e.date', 'DESC')
            ->orderBy('e.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $user_id
     * @param $year
     * @return Expense[] Returns an array of Expense objects
     */
    public function getIncomesForYearByUserId($user_id, $year): array
    {
        $from = $year . '-01-01';
        $to = $year . '-12-31';

        return $this->createQueryBuilder('e')
            ->andWhere('e.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->andWhere('e.category = 25')
            ->andWhere('e.amIPayer = :amIPayer')
            ->setParameter('amIPayer', false)
            ->andWhere('e.date BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('e.date', 'DESC')
            ->orderBy('e.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $user_id
     * @param $num
     * @return Expense[] Returns an array of Expense objects
     */

    public function getLastNumberOfTransactions($user_id, $num): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->orderBy('e.date', 'DESC')
            ->orderBy('e.created_at', 'DESC')
            ->setMaxResults($num)
            ->getQuery()
            ->getResult();
    }

    public function getTransactionsForCategoryForMonthByUserId($user_id, $year, $month, $category_id, $amIPayer = true): array
    {
        $numOfDays = date("t", mktime(0, 0, 0, $month, 3, $year));
        $from = $year . '-' . $month . '-01';
        $to = $year . '-' . $month . '-' . $numOfDays;

        return $this->createQueryBuilder('e')
            ->andWhere('e.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->andWhere('e.category = :category')
            ->setParameter('category', $category_id)
            ->andWhere('e.amIPayer = :amIPayer')
            ->setParameter('amIPayer', $amIPayer)
            ->andWhere('e.date BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)

            ->andWhere('e.category != 25')
            ->orderBy('e.created_at', 'DESC')
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
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
