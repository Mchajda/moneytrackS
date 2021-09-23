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
     * @Route("/transactions/{year}/{month}", name="transactions")
     */
    public function index($year, $month): Response
    {
        $alert = "";
        $alert_class = "";

        return $this->render('transactions/index.html.twig', [
            'current_year' => $year, 'current_month' => $month,
            'alert' => $alert, 'alert_class' => $alert_class,
            'transactions' => $this->expensesProvider->getAllForMonthByUserId($this->getUser()->getId(), $year, $month, true),
        ]);
    }
}
