<?php

namespace App\Controller;

use App\Provider\Interfaces\CategoryProviderInterface;
use App\Provider\Interfaces\ExpensesProviderInterface;
use App\Utils\DateProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SummaryController extends AbstractController
{
    private $expensesProvider;
    private $categoryProvider;
    private DateProvider $dateProvider;

    public function __construct(
        ExpensesProviderInterface $expensesProvider,
        CategoryProviderInterface $categoryProvider,
        DateProvider $dateProvider
    )
    {
        $this->dateProvider = $dateProvider;
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
        $dateData = $this->dateProvider->countPreviousMonth($year, $month);
        dd($dateData);

        $current_year = $year;
        $current_month = $month;

        $this_month_expenses = $this->expensesProvider->getAllOrderedByMainCategoriesByUserId($user->getId(), $current_year, $current_month, true);
        $this_month_expenses_for_chart = array_values($this_month_expenses);

        if($month <= 10)
            $prev_month = "0".strval($month-1);
        else $prev_month = strval($month-1);

        $previous_month_expenses = $this->expensesProvider->getAllOrderedByMainCategoriesByUserId($user->getId(), $current_year, $prev_month, true);
        $categories = $this->categoryProvider->getAllParentCategories();
        $categoriesLabels = $this->categoryProvider->getAllParentCategoriesNames();
        $categories_colors = $this->categoryProvider->getCategoriesColors();

        $months = ['1' => 'styczeń', '2' => 'luty', '3' => 'marzec', '4' => 'kwiecień', '5' => 'maj', '6' => 'czerwiec', '7' => 'lipiec', '8' => 'sierpień', '9' => 'wrzesień', '10' => 'październik', '11' => 'listopad', '12' => 'grudzień'];
        $monthly_expenses = $this->expensesProvider->getMonthlyExpensesForYearByUser($user->getId(), $current_year);

        return $this->render('summary/index.html.twig', [
            'current_year' => $year, 'current_month' => $month,
            'alert' => $alert, 'alert_class' => $alert_class,
            'categories' => $categories, 'this_month_expenses' => $this_month_expenses,
            'previous_month_expenses' => $previous_month_expenses,
            'categories_for_chart' => $categoriesLabels, 'categories_colors' => $categories_colors, 'expenses_for_chart' => $this_month_expenses_for_chart,
            'months' => array_values($months), 'monthly_expenses' => $monthly_expenses,
        ]);
    }
}
