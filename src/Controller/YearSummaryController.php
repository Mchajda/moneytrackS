<?php

namespace App\Controller;

use App\Provider\ExpensesProvider;
use App\Utils\DateProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class YearSummaryController extends AbstractController
{
    private $dateProvider;
    private $expenseProvider;

    /**
     * @param $dateProvider
     */
    public function __construct(
        DateProvider     $dateProvider,
        ExpensesProvider $expenseProvider
    )
    {
        $this->dateProvider = $dateProvider;
        $this->expenseProvider = $expenseProvider;
    }

    /**
     * @Route("/year-summary/{year}", name="year_summary")
     */
    public function index(Request $request, $year): Response
    {
        $alert = $request->request->get('alert');
        $alertClass = $request->request->get('alert-class');
        $userId = $this->getUser()->getId();
        $todayDate = $this->dateProvider->getTodayDate();

        $yearTotalIncomes = $this->expenseProvider->getSumOfIncomesForYearByUserId($userId, $year);
        $yearTotalExpenses = $this->expenseProvider->getSumOfExpensesForYearByUserId($userId, $year);

dd($this->expenseProvider->getTransactionsForCategoryForMonthByUserId($userId, $year, 1, 1));

        return $this->render('year_summary/index.html.twig', [
            'alert' => $alert, 'alertClass' => $alertClass,
            'this_year' => $todayDate['year'], 'this_month' => $todayDate['month'],
            'year' => $year,
            'yearTotalIncomes' => $yearTotalIncomes, 'yearTotalExpenses' => $yearTotalExpenses
        ]);
    }
}
