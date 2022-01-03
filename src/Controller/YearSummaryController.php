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
        $months = ['1' => 'styczeń', '2' => 'luty', '3' => 'marzec', '4' => 'kwiecień', '5' => 'maj', '6' => 'czerwiec', '7' => 'lipiec', '8' => 'sierpień', '9' => 'wrzesień', '10' => 'październik', '11' => 'listopad', '12' => 'grudzień'];

        $yearTotalIncomes = $this->expenseProvider->getSumOfIncomesForYearByUserId($userId, $year);
        $yearTotalExpenses = $this->expenseProvider->getSumOfExpensesForYearByUserId($userId, $year);

        $categoriesByMonths = $this->expenseProvider->getTransactionsByMonthsForWholeYear($userId, $year);
//        dd($categoriesByMonths['eating out']);

        return $this->render('year_summary/index.html.twig', [
            'alert' => $alert, 'alertClass' => $alertClass,
            'this_year' => $todayDate['year'], 'this_month' => $todayDate['month'],
            'year' => $year, 'months' => array_values($months),
            'yearTotalIncomes' => $yearTotalIncomes, 'yearTotalExpenses' => $yearTotalExpenses,
            'categoriesByMonths' => $categoriesByMonths
        ]);
    }
}
