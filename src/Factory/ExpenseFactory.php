<?php


namespace App\Factory;


use App\Entity\Expense;

class ExpenseFactory
{
    public static function create(
        $user_id,
        $title,
        $date,
        $category,
        $recipient,
        $amount,
        $direction,
        $amIPayer
    )
    {
        $expense = new Expense();

        $expense->setUserId($user_id);
        $expense->setTitle($title);
        $expense->setDate($date);
        $expense->setCategory($category);
        $expense->setRecipient($recipient);
        $expense->setAmount($amount);
        $expense->setDirection($direction);

        if ($direction == "expense" && $amIPayer == "self") {
            $expense->setAmIPayer(true);
        } else if ($direction == "expense" && $amIPayer != "self") {
            $expense->setAmIPayer(false);
        } else if ($direction == "income") {
            $expense->setAmIPayer(false);
        }

        $expense->setCreatedAt(new \DateTime());
        $expense->setUpdatedAt(new \DateTime());

        return $expense;
    }
}