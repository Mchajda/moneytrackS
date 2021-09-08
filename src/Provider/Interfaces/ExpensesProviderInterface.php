<?php


namespace App\Provider\Interfaces;


interface ExpensesProviderInterface
{
    public function getAll($user_id);
    public function getAllForMonth($user_id, $year, $month);
    public function getAllForYear($user_id, $year);
    public function getAllOrderedByCategories($user_id, $year, $month, $categories);
    public function getLast($user_id, $num);
    public function getMonthlyExpenses($user_id, $year);
    public function getSumOfMonthExpenses($user_id, $year, $month): float;
    public function getSumOfMonthIncomes($user_id, $year, $month): float;
}