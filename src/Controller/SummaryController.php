<?php

namespace App\Controller;

use App\Provider\Interfaces\CategoryProviderInterface;
use App\Provider\Interfaces\ExpensesProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SummaryController extends AbstractController
{
    private $expensesProvider;
    private $categoryProvider;

    public function __construct(ExpensesProviderInterface $expensesProvider, CategoryProviderInterface $categoryProvider)
    {
        $this->expensesProvider = $expensesProvider;
        $this->categoryProvider = $categoryProvider;
    }

    /**
     * @Route("/summary/{year}/{month}", name="summary")
     */
    public function index($year, $month): Response
    {
        $user = $this->getUser();
        $alert = "";
        $alert_class = "";

        $current_year = $year;
        $current_month = $month;

        $this_month_expenses = $this->expensesProvider->getAllOrderedByCategories($user->getId(), $current_year, $current_month, $this->categoryProvider->getAllCategories());
        $this_month_expenses_for_chart = array_values($this_month_expenses);

        if($month <= 10)
            $prev_month = "0".strval($month-1);
        else $prev_month = strval($month-1);

        $previous_month_expenses = $this->expensesProvider->getAllOrderedByCategories($user->getId(), $current_year, $prev_month, $this->categoryProvider->getAllCategories());
        $categories = $this->categoryProvider->getAllCategoriesNames();
        $categories_colors = $this->categoryProvider->getCategoriesColors();

        $months = ['1' => 'styczeń', '2' => 'luty', '3' => 'marzec', '4' => 'kwiecień', '5' => 'maj', '6' => 'czerwiec', '7' => 'lipiec', '8' => 'sierpień', '9' => 'wrzesień', '10' => 'październik', '11' => 'listopad', '12' => 'grudzień'];
        $monthly_expenses = $this->expensesProvider->getMonthlyExpenses($user->getId(), $current_year);

        $catValues = $this->expensesProvider->getForPredictions();
        $categoriesForAvgs = $this->categoryProvider->getAllCategories();
        $avgs = array_values($this->expensesProvider->countAverageValues($catValues, $categoriesForAvgs));

        return $this->render('summary/index.html.twig', [
            'current_year' => $year, 'current_month' => $month,
            'alert' => $alert, 'alert_class' => $alert_class,
            'categories' => $categories, 'this_month_expenses' => $this_month_expenses,
            'previous_month_expenses' => $previous_month_expenses,
            'categories_for_chart' => $categories, 'categories_colors' => $categories_colors, 'expenses_for_chart' => $this_month_expenses_for_chart,
            'months' => array_values($months), 'monthly_expenses' => $monthly_expenses, 'averages' => $avgs,
        ]);
    }
}
