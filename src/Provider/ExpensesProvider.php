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

    public function getAllOrderedByCategories($user_id, $year, $month, $categories)
    {
        if($month == 0) //zabezpieczenie w przypadku previous month przypadku grudzien-styczen
        {
            $month = 12;
            $year -= 1;
        }

        $this_month_expenses = [];
        $expenses = $this->getAll($user_id, $year, $month);

        foreach($categories as $cat)
        {
            $this_month_expenses[$cat->getCategoryName()] = 0;
            foreach($expenses as $expense){
                if($expense->getCategory() == $cat->getCategoryName() && $expense->getDirection() == "expense")
                    $this_month_expenses[$cat->getCategoryName()] += $expense->getAmount();
            }
        }
        return $this_month_expenses;
    }

    public function getLast($user_id, $num)
    {
        return $this->repository->getLast($user_id, $num);
    }
}