<?php


namespace App\RequestProcessor\Interfaces;


use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

interface ExpenseRequestProcessorInterface
{
    public function create(Request $request, UserInterface $user);
}