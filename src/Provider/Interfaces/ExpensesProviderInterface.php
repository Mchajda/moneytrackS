<?php


namespace App\Provider\Interfaces;


interface ExpensesProviderInterface
{
    public function getAllByUserId($user_id): array;

    public function getAllForYearByUserId($user_id, $year): array;

    public function getAllForMonthByUserId($user_id, $year, $month): array;

    public function getAllOrderedByMainCategoriesByUserId($user_id, $year, $month): array;

    public function getAllOrderedByCategoriesByUserId($user_id, $year, $month): array;

    public function getLastExpensesByUserId($user_id, $num): array;

    public function getMonthlyExpensesForYearByUser($user_id, $year): array;

    public function getSumOfMonthExpensesByUser($user_id, $year, $month): float;

    public function getSumOfMonthIncomesByUser($user_id, $year, $month): float;
}