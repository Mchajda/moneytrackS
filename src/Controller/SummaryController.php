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
        DateProvider              $dateProvider
    )
    {
        $this->dateProvider = $dateProvider;
        $this->expensesProvider = $expensesProvider;
        $this->categoryProvider = $categoryProvider;
    }

    /**
     * @Route("/summary/{this_year}/{this_month}", name="summary")
     */
    public function index($this_year, $this_month): Response
    {
        $user = $this->getUser();
        $alert = "";
        $alert_class = "";
        $dateData = $this->dateProvider->countPreviousMonth($this_year, $this_month);
        $previous_month = $dateData['previous_month']['month'];
        $previous_year = $dateData['previous_month']['year'];

        $this_month_expenses = $this->expensesProvider->getAllOrderedByMainCategoriesByUserId($user->getId(), $dateData['this_month']['year'], $dateData['this_month']['month'], true);
        $this_month_expenses_for_chart = array_values($this_month_expenses);

        if ($this_month <= 10)
            $prev_month = "0" . strval($this_month - 1);
        else $prev_month = strval($this_month - 1);

        $previous_month_expenses = $this->expensesProvider->getAllOrderedByMainCategoriesByUserId($user->getId(), $dateData['this_month']['year'], $previous_month, true);
        $categories = $this->categoryProvider->getAllParentCategories();
        $categoriesLabels = $this->categoryProvider->getAllParentCategoriesNames();
        $categories_colors = $this->categoryProvider->getCategoriesColors();

        $months = ['1' => 'styczeń', '2' => 'luty', '3' => 'marzec', '4' => 'kwiecień', '5' => 'maj', '6' => 'czerwiec', '7' => 'lipiec', '8' => 'sierpień', '9' => 'wrzesień', '10' => 'październik', '11' => 'listopad', '12' => 'grudzień'];
        $monthly_expenses = $this->expensesProvider->getMonthlyExpensesForYearByUser($user->getId(), $this_year);
        $monthly_incomes = $this->expensesProvider->getMonthlyIncomesForYearByUser($user->getId(), $this_year);

        return $this->render('summary/index.html.twig', [
            'this_year' => $this_year, 'this_month' => $this_month,
            'previous_year' => $previous_year, 'previous_month' => $previous_month,
            'alert' => $alert, 'alert_class' => $alert_class,
            'categories' => $categories, 'this_month_expenses' => $this_month_expenses,
            'previous_month_expenses' => $previous_month_expenses,
            'categories_for_chart' => $categoriesLabels, 'categories_colors' => $categories_colors, 'expenses_for_chart' => $this_month_expenses_for_chart,
            'months' => array_values($months), 'monthly_expenses' => $monthly_expenses, 'monthly_incomes' => $monthly_incomes,
        ]);
    }
}
