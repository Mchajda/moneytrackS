<?php


namespace App\RequestProcessor\Interfaces;


use App\Entity\Expense;
use Symfony\Component\Security\Core\User\UserInterface;

interface ExpenseRequestProcessorInterface
{
    public function create(array $params, UserInterface $user): Expense;
}