<?php


namespace App\Provider;


use App\Provider\Interfaces\ExpensesProviderInterface;
use App\Repository\ExpenseRepository;

class ExpensesProvider implements ExpensesProviderInterface
{
    private $repository;

    public function __construct(ExpenseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($user_id, $year, $month)
    {
        return $this->repository->getForDate($user_id, $year, $month);
    }

    public function getLast($user_id, $num)
    {
        return $this->repository->getLast($user_id, $num);
    }
}