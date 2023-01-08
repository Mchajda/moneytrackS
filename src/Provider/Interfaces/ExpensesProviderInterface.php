<?php


namespace App\Provider\Interfaces;


interface ExpensesProviderInterface
{
    public function getAllByUserId($user_id): array;

    public function getExpensesForYearByUserId($user_id, $year, $amIPayer = true): array;

    public function getSumOfExpensesForYearByUserId($user_id, $year, $amIPayer = true): float;

    public function getExpensesForMonthByUserId($user_id, $year, $month, $amIPayer): array;

    public function getIncomesForMonthByUserId($user_id, $year, $month): array;

    public function getSumOfIncomesForYearByUserId($user_id, $year): float;

    public function getAllOrderedByMainCategoriesByUserId($user_id, $year, $month, $amIPayer): array;

    public function getExpensesByMainCategoriesByUserIdList($user_id, $year, $month, $amIPayer): array;

    public function getLastExpensesByUserId($user_id, $num): array;

    public function getMonthlyExpensesForYearByUser($user_id, $year, $amIPayer): array;

    public function getMonthlyIncomesForYearByUser($user_id, $year): array;

    //new functions
    public function getSumOfMonthExpensesByUserId($user_id, $year, $month): float;

    public function getSumOfMonthIncomesByUserId($user_id, $year, $month): float;

    public function getExpensesIncomesDiffForMonthByUserId($user_id, $year, $month): float;

    public function getExpensesIncomesDiffAggregatedForYearByUserId($user_id, $year): array;

    public function countTransactionsValue(array $transactions): float;

    public function getTransactionsForCategoryForMonthByUserId($user_id, $year, $month, $category_name): array;

    public function getTransactionsByMonthsForWholeYear($user_id, $year): array;
}