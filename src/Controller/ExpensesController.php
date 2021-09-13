<?php

namespace App\Controller;

use App\Provider\Interfaces\CategoryProviderInterface;
use App\Provider\Interfaces\ExpensesProviderInterface;
use App\RequestProcessor\Interfaces\ExpenseRequestProcessorInterface;
use App\Service\Interfaces\ExpensesServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExpensesController extends AbstractController
{
    private $categoryProvider;
    private $requestProcessor;
    private $expenseService;
    private $expensesProvider;

    public function __construct(
        CategoryProviderInterface $categoryProvider,
        ExpenseRequestProcessorInterface $expenseRequestProcessor,
        ExpensesServiceInterface $expenseService,
        ExpensesProviderInterface $expensesProvider
    )
    {
        $this->categoryProvider = $categoryProvider;
        $this->requestProcessor = $expenseRequestProcessor;
        $this->expenseService = $expenseService;
        $this->expensesProvider = $expensesProvider;
    }

    /**
     * @Route("/add-expense", name="add_expense_form")
     */
    public function index(Request $request): Response
    {
        $alert = (string)$request->query->get('alert');
        $alert_class = (string)$request->query->get('alert_class');

        $current_year = date('Y');
        $current_month = date('m');
        $current_day = date('d');

        return $this->render('expenses/index.html.twig', [
            'balance' => $this->getUser()->getBalance(),
            'expenses' => $this->expensesProvider->getLast($this->getUser()->getId(),5),
            'current_year' => $current_year, 'current_month' => $current_month, 'current_day' => $current_day,
            'categories' => $this->categoryProvider->getAllCategories(),
            'alert' => $alert, 'alert_class' => $alert_class,
        ]);
    }

    /**
     * @Route("/store_expense/{year}/{month}", name="add_expense")
     */
    public function store(Request $request): Response
    {
        dd($request->request->all());
        if($request->isMethod("POST"))
        {
            $expense = $this->requestProcessor->create($request, $this->getUser());
            $this->expenseService->create($expense);

            if($request->request->get('direction') == "expense")
                $this->getUser()->setBalance($this->getUser()->getBalance() - $request->request->get('amount'));
            else $this->getUser()->setBalance($this->getUser()->getBalance() + $request->request->get('amount'));

            $alert = "Expense ".$expense->getTitle()." added successfully";
            $alert_class = "alert-success";

        }
        else
        {
            $alert = "Operation failed";
            $alert_class = "alert-danger";
        }

        $current_year = date('Y');
        $current_month = date('m');
        $current_day = date('d');

        return $this->redirectToRoute('add_expense_form', ['year' => $current_year, 'month' => $current_month, 'day' => $current_day, 'alert' => $alert, 'alert_class' => $alert_class]);
    }
}
