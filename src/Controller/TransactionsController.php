<?php

namespace App\Controller;

use App\Provider\Interfaces\CategoryProviderInterface;
use App\Provider\Interfaces\ExpensesProviderInterface;
use App\Service\Interfaces\ExportServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionsController extends AbstractController
{
    private $expensesProvider;
    private $categoryProvider;
    private $exportService;

    public function __construct(ExpensesProviderInterface $expensesProvider, CategoryProviderInterface $categoryProvider, ExportServiceInterface $exportService)
    {
        $this->expensesProvider = $expensesProvider;
        $this->categoryProvider = $categoryProvider;
        $this->exportService = $exportService;
    }

    /**
     * @Route("/transactions/{year}/{month}", name="transactions")
     */
    public function index(Request $request, $year, $month): Response
    {
        $alert = (string)$request->query->get('alert');
        $alert_class = (string)$request->query->get('alert_class');

        return $this->render('transactions/index.html.twig', [
            'current_year' => $year, 'current_month' => $month,
            'alert' => $alert, 'alert_class' => $alert_class,
            'transactions' => $this->expensesProvider->getAllForMonth($this->getUser()->getId(), $year, $month),
        ]);
    }

    /**
     * @Route("/export_transactions_to_json/{year}/{month}", name="export_transactions_to_json")
     */
    public function exportToJSON($year, $month): Response
    {
        $alert = "expenses successfully exported to json file!";
        $alert_class = "alert-success";

        $transactions = $this->expensesProvider->getAllForMonth($this->getUser()->getId(), $year, $month);
        $this->exportService->exportToJson($transactions);

        return $this->redirectToRoute('transactions', [
            'year' => $year, 'month' => $month,
            'alert' => $alert, 'alert_class' => $alert_class,
        ]);
    }
}
