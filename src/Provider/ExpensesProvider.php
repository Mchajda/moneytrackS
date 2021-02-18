<?php


namespace App\Provider;


use App\Provider\Interfaces\ExpensesProviderInterface;
use App\Repository\ExpenseRepository;
use DateTime;

class ExpensesProvider implements ExpensesProviderInterface
{
    private $repository;

    public function __construct(ExpenseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($user_id)
    {
        return $this->repository->findBy(['user_id' => $user_id]);
    }

    public function getAllForYear($user_id, $year)
    {
        return $this->repository->getForYear($user_id, $year, "expense");
    }

    public function getAllForMonth($user_id, $year, $month)
    {
        return $this->repository->getForDate($user_id, $year, $month, "expense");
    }

    public function getAllOrderedByCategories($user_id, $year, $month, $categories)
    {
        if($month == 0) //zabezpieczenie w przypadku previous month przypadku grudzien-styczen
        {
            $month = 12;
            $year -= 1;
        }

        $this_month_expenses = [];
        $expenses = $this->getAllForMonth($user_id, $year, $month);

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

    public function getMonthlyExpenses($user_id, $year)
    {
        $expenses = $this->getAllForYear($user_id, $year);
        //wydatki posegregowane na miesiace np do rocznego zestawienia
        $monthly_expenses = [];

        for($i=0 ; $i<12 ; $i++){
            $monthly_sum = 0;
            foreach($expenses as $expense){
                $date = DateTime::createFromFormat('Y-m-d', $expense->getDate());
                if($date->format('n') == $i+1)
                    $monthly_sum += $expense->getAmount();
            }
            $monthly_expenses[$i] = $monthly_sum;
        }

        return $monthly_expenses;
    }

    public function getForPredictions()
    {
        return $this->repository->forPredictions();
    }

    public function countAverageValues($catValues, $categories)
    {
        $avgs = []; $i=1;
        foreach ($categories as $cat) {
            $avgs[$cat->getCategoryName()] = 0;
            foreach ($catValues as $catValue){
                if($catValue['category'] == $cat->getCategoryName()) {
                    $avgs[$cat->getCategoryName()] += $catValue['suma'];
                    $i++;
                }
                $avgs[$cat->getCategoryName()] = $avgs[$cat->getCategoryName()]/$i; $i=1;
            }
        }
        return $avgs;
    }
}