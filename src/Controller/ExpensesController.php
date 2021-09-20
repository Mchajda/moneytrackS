<?php

namespace App\Controller;

use App\Provider\Interfaces\CategoryProviderInterface;
use App\Provider\Interfaces\ExpensesProviderInterface;
use App\RequestProcessor\Interfaces\ExpenseRequestProcessorInterface;
use App\Service\Interfaces\ExpensesServiceInterface;
use App\Utils\DateProvider;
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
        CategoryProviderInterface        $categoryProvider,
        ExpenseRequestProcessorInterface $expenseRequestProcessor,
        ExpensesServiceInterface         $expenseService,
        ExpensesProviderInterface        $expensesProvider
    )
    {
        $this->categoryProvider = $categoryProvider;
        $this->requestProcessor = $expenseRequestProcessor;
        $this->expenseService = $expenseService;
        $this->expensesProvider = $expensesProvider;
    }

    /**
     * @Route("/add-expense", name="add_expense_form", methods={"GET|POST"})
     */
    public function index(Request $request, DateProvider $dateProvider): Response
    {
        $alert = (string)$request->query->get('alert');
        $alert_class = (string)$request->query->get('alert_class');
        $todayDate = $dateProvider->getTodayDate();

        if ($request->isMethod("POST")) {
            $params = $request->request->all();
            $expense = $this->requestProcessor->create($params, $this->getUser());
            //try catch?
            $this->expenseService->create($expense);
            $lastUsedDate = $dateProvider->getLastUsedDate($expense->getDate());

            $alert = "Expense " . $expense->getTitle() . " added successfully!";
            $alert_class = "alert-success";
        }

        $date = $lastUsedDate ?? $todayDate;

        return $this->render('expenses/index.html.twig', [
            'current_year' => $date['year'], 'current_month' => $date['month'], 'current_day' => $date['day'],
            'expenses' => $this->expensesProvider->getLastExpensesByUserId($this->getUser()->getId(), 5),
            'categories' => $this->categoryProvider->getAllParentCategories(),
            'alert' => $alert, 'alert_class' => $alert_class,
        ]);
    }
}
