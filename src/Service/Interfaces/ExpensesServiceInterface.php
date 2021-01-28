<?php


namespace App\Service\Interfaces;


use App\Entity\Expense;

interface ExpensesServiceInterface
{
    public function create(Expense &$expense): void;
}