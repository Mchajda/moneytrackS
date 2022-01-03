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

    public function getExpensesForYearByUserId($user_id, $year, $amIPayer = true): array
    {
        return $this->repository->getExpensesForYearByUserId($user_id, $year, $amIPayer);
    }

    public function getSumOfExpensesForYearByUserId($user_id, $year, $amIPayer = true): float
    {
        $expenses = $this->repository->getExpensesForYearByUserId($user_id, $year, $amIPayer);
        return $this->countTransactionsValue($expenses);
    }

    public function getExpensesForMonthByUserId($user_id, $year, $month, $amIPayer = true): array
    {
        return $this->repository->getExpensesForMonth($user_id, $year, $month, $amIPayer);
    }

    public function getIncomesForMonthByUserId($user_id, $year, $month): array
    {
        return $this->repository->getIncomesForMonth($user_id, $year, $month);
    }

    public function getSumOfIncomesForYearByUserId($user_id, $year): float
    {
        $incomes = $this->repository->getIncomesForYearByUserId($user_id, $year);
        return $this->countTransactionsValue($incomes);
    }

    public function getAllOrderedByMainCategoriesByUserId($user_id, $year, $month, $amIPayer = true): array
    {
        if ($month == 0) //zabezpieczenie w przypadku previous month przypadku grudzien-styczen
        {
            $month = 12;
            $year -= 1;
        }

        $categories = $this->categoryProvider->getAllParentCategories();
        $expenses = $this->getExpensesForMonthByUserId($user_id, $year, $month, $amIPayer);
        $this_month_expenses = [];

        foreach ($categories as $category) {
            $this_month_expenses[$category->getCategoryName()] = 0;
            foreach ($expenses as $expense) {
                if ($expense->getCategory() == $category || $expense->getCategory()->getParentCategory() == $category) {
                    $this_month_expenses[$category->getCategoryName()] += $expense->getAmount();
                }
            }
            round($this_month_expenses[$category->getCategoryName()], 2);
        }

        return $this_month_expenses;
    }

    public function getLastExpensesByUserId($user_id, $num): array
    {
        return $this->repository->getLastNumberOfTransactions($user_id, $num);
    }

    public function getMonthlyExpensesForYearByUser($user_id, $year, $amIPayer = true): array
    {
        $expenses = $this->repository->getExpensesForYearByUserId($user_id, $year, $amIPayer);
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

    public function getMonthlyIncomesForYearByUser($user_id, $year): array
    {
        $incomes = $this->repository->getIncomesForYearByUserId($user_id, $year);
        //wydatki posegregowane na miesiace np do rocznego zestawienia
        $monthly_incomes = [];

        for ($i = 0; $i < 12; $i++) {
            $monthly_sum = 0;
            foreach ($incomes as $income) {
                $date = DateTime::createFromFormat('Y-m-d', $income->getDate());
                if ($date->format('n') == $i + 1)
                    $monthly_sum += $income->getAmount();
            }
            $monthly_incomes[$i] = $monthly_sum;
        }

        return $monthly_incomes;
    }

    //new functions
    public function getSumOfMonthExpensesByUserId($user_id, $year, $month): float
    {
        $sumExpenses = 0;
        $expenses = $this->getExpensesForMonthByUserId($user_id, $year, $month, true);
        foreach ($expenses as $expense) {
            $sumExpenses += $expense->getAmount();
        }
        return $sumExpenses;
    }

    public function getSumOfMonthIncomesByUserId($user_id, $year, $month): float
    {
        $sumIncomes = 0;
        $incomes = $this->getIncomesForMonthByUserId($user_id, $year, $month);
        foreach ($incomes as $income) {
            $sumIncomes += $income->getAmount();
        }
        return $sumIncomes;
    }

    public function getExpensesIncomesDiffForMonthByUserId($user_id, $year, $month): float
    {
        return $this->getSumOfMonthIncomesByUserId($user_id, $year, $month) - $this->getSumOfMonthExpensesByUserId($user_id, $year, $month);
    }

    public function getExpensesIncomesDiffAggregatedForYearByUserId($user_id, $year): array
    {
        $aggregatedDiff = [];
        $monthsIndexes = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

        foreach ($monthsIndexes as $key => $monthIndex) {
            if ($key == 0) {
                $aggregatedDiff[] = $this->getExpensesIncomesDiffForMonthByUserId($user_id, $year, $monthIndex);
            } else {
                $aggregatedDiff[] = $this->getExpensesIncomesDiffForMonthByUserId($user_id, $year, $monthIndex) + $aggregatedDiff[$key - 1];
            }
        }
        return $aggregatedDiff;
    }

    public function countTransactionsValue(array $transactions): float
    {
        $transactionsValue = 0;

        foreach ($transactions as $transaction) {
            $transactionsValue += $transaction->getAmount();
        }

        return $transactionsValue;
    }

    public function getTransactionsForCategoryForMonthByUserId($user_id, $year, $month, $direction, $category_name): array
    {
        $category = $this->categoryProvider->getOneByName($category_name);

        $expenses = $this->repository->getTransactionsForCategoryForMonthByUserId($user_id, $year, $month, $direction, $category->getId());
        $transactionsValue = $this->countTransactionsValue($expenses);
        return [
            'expenses' => $expenses,
            'expenses_value' => $transactionsValue
        ];
    }
}