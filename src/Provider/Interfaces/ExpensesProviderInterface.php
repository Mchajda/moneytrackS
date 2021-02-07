<?php


namespace App\Provider\Interfaces;


interface ExpensesProviderInterface
{
    public function getAll($user_id, $year, $month);
    public function getAllOrderedByCategories($user_id, $year, $month, $categories);
    public function getLast($user_id, $num);
}