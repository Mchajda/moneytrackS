<?php

namespace App\Controller;

use App\Provider\Interfaces\ExpensesProviderInterface;
use App\Service\Interfaces\ExpensesServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionsController extends AbstractController
{
    private $expensesProvider;

    public function __construct(ExpensesProviderInterface $expensesProvider)
    {
        $this->expensesProvider = $expensesProvider;
    }

    /**
     * @Route("/transactions/{this_year}/{this_month}", name="transactions")
     */
    public function index($this_year, $this_month): Response
    {
        $alert = "";
        $alert_class = "";

        return $this->render('transactions/index.html.twig', [
            'this_year' => $this_year, 'this_month' => $this_month,
            'alert' => $alert, 'alert_class' => $alert_class,
            'transactions' => $this->expensesProvider->getExpensesForMonthByUserId($this->getUser()->getId(), $this_year, $this_month, true),
        ]);
    }
}
