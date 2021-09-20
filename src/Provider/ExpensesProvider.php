<?php


namespace App\Provider;


use App\Provider\Interfaces\CategoryProviderInterface;
use App\Provider\Interfaces\ExpensesProviderInterface;
use App\Repository\ExpenseRepository;
use DateTime;

class ExpensesProvider implements ExpensesProviderInterface
{
    /**
     * @var CategoryProviderInterface
     */
    private CategoryProviderInterface $categoryProvider;

    /**
     * @var ExpenseRepository
     */
    private ExpenseRepository $repository;

    public function __construct(ExpenseRepository $repository, CategoryProviderInterface $categoryProvider)
    {
        $this->repository = $repository;
        $this->categoryProvider = $categoryProvider;
    }

    public function getAllByUserId($user_id): array
    {
        return $this->repository->findBy(['user_id' => $user_id]);
    }

    public function getAllForYearByUserId($user_id, $year): array
    {
        return $this->repository->getForYear($user_id, $year, "expense");
    }

    public function getAllForMonthByUserId($user_id, $year, $month, $amIPayer): array
    {
        return $this->repository->getForDate($user_id, $year, $month, "expense", $amIPayer);
    }

    public function getAllOrderedByMainCategoriesByUserId($user_id, $year, $month, $amIPayer): array
    {
        if ($month == 0) //zabezpieczenie w przypadku previous month przypadku grudzien-styczen
        {
            $month = 12;
            $year -= 1;
        }

        $categories = $this->categoryProvider->getAllParentCategories();
        $expenses = $this->getAllForMonthByUserId($user_id, $year, $month, $amIPayer);
        $this_month_expenses = [];

        foreach ($categories as $category) {
            $this_month_expenses[$category->getCategoryName()] = 0;
            foreach ($expenses as $expense) {
                if ($expense->getCategory() == $category || $expense->getCategory()->getParentCategory() == $category) {
                    $this_month_expenses[$category->getCategoryName()] += $expense->getAmount();
                }
            }
        }

        return $this_month_expenses;
    }

    public function getAllOrderedByCategoriesByUserId($user_id, $year, $month): array
    {
        $categories = $this->categoryProvider->getAllCategories();
        if ($month == 0) //zabezpieczenie w przypadku previous month przypadku grudzien-styczen
        {
            $month = 12;
            $year -= 1;
        }

        $this_month_expenses = [];
        $expenses = $this->getAllForMonthByUserId($user_id, $year, $month);

        foreach ($categories as $cat) {
            $this_month_expenses[$cat->getCategoryName()] = 0;
            foreach ($expenses as $expense) {
                if ($expense->getCategory() == $cat->getCategoryName() && $expense->getDirection() == "expense")
                    $this_month_expenses[$cat->getCategoryName()] += $expense->getAmount();
            }
        }
        return $this_month_expenses;
    }

    public function getLastExpensesByUserId($user_id, $num): array
    {
        return $this->repository->getLast($user_id, $num);
    }

    public function getMonthlyExpensesForYearByUser($user_id, $year): array
    {
        $expenses = $this->getAllForYearByUserId($user_id, $year);
        //wydatki posegregowane na miesiace np do rocznego zestawienia
        $monthly_expenses = [];

        for ($i = 0; $i < 12; $i++) {
            $monthly_sum = 0;
            foreach ($expenses as $expense) {
                $date = DateTime::createFromFormat('Y-m-d', $expense->getDate());
                if ($date->format('n') == $i + 1)
                    $monthly_sum += $expense->getAmount();
            }
            $monthly_expenses[$i] = $monthly_sum;
        }

        return $monthly_expenses;
    }

    public function getSumOfMonthExpensesByUserId($user_id, $year, $month): float
    {
        $sumExpenses = 0;
        $expenses = $this->repository->getForDate($user_id, $year, $month, "expense");
        foreach ($expenses as $expense) {
            $sumExpenses += $expense->getAmount();
        }
        return $sumExpenses;
    }

    public function getSumOfMonthIncomesByUserId($user_id, $year, $month): float
    {
        $sumIncomes = 0;
        $incomes = $this->repository->getForDate($user_id, $year, $month, "income", false);
        foreach ($incomes as $income) {
            $sumIncomes += $income->getAmount();
        }
        return $sumIncomes;
    }
}