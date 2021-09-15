<?php


namespace App\RequestProcessor;


use App\Entity\User;
use App\Factory\ExpenseFactory;
use App\RequestProcessor\Interfaces\ExpenseRequestProcessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class ExpenseRequestProcessor implements ExpenseRequestProcessorInterface
{
    public function create(Request $request, UserInterface $user, $category)
    {
        $user_id = $user->getId();
        $title = $request->request->get('title');
        $date = $request->request->get('date');
        $recipient = $request->request->get('recipient');
        $amount = $request->request->get('amount');
        $direction = $request->request->get('direction');
        $amIPayer = $request->request->get('payer');

        return ExpenseFactory::create(
            $user_id,
            $title,
            $date,
            $category,
            $recipient,
            $amount,
            $direction,
            $amIPayer
        );
    }
}