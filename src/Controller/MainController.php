<?php

namespace App\Controller;

use App\Provider\Interfaces\CategoryProviderInterface;
use App\Provider\Interfaces\ExpensesProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private $expensesProvider;
    private $categoryProvider;

    public function __construct(ExpensesProviderInterface $expensesProvider, CategoryProviderInterface $categoryProvider)
    {
        $this->expensesProvider = $expensesProvider;
        $this->categoryProvider = $categoryProvider;
    }

    /**
     * @Route("/", name="login_form")
     */
    public function loginForm(): Response
    {
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/profile", name="main")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $current_year = date('Y');
        $current_month = date('m');

        $alert = "";
        $alert_class = "";

        $this_month_expenses = array_values($this->expensesProvider->getAllOrderedByMainCategories($user->getId(), $current_year, $current_month,));
        $categories = $this->categoryProvider->getAllCategoriesNames();
        $categories_colors = $this->categoryProvider->getCategoriesColors();

        $thisMonthSumOfExpenses = $this->expensesProvider->getSumOfMonthExpenses($user->getId(), $current_year, $current_month);
        $thisMonthSumOfIncomes = $this->expensesProvider->getSumOfMonthIncomes($user->getId(), $current_year, $current_month);

        return $this->render('main/index.html.twig', [
            'user' => $user, 'this_month_expenses' => $this_month_expenses,
            'expenses' => $this->expensesProvider->getLast($user->getId(),5),
            'current_year' => $current_year, 'current_month' => $current_month,
            'expenses_for_chart' => $this_month_expenses, 'categories_for_chart' => $categories, 'categories_colors' => $categories_colors,
            'alert' => $alert, 'alert_class' => $alert_class,
            'thisMonthSumOfExpenses' => $thisMonthSumOfExpenses, 'thisMonthSumOfIncomes' => $thisMonthSumOfIncomes
        ]);
    }

    /**
     * @Route("/debug", name="debug")
     */
    public function debug(): Response
    {
        $user = $this->getUser();
        $current_year = date('Y');
        $current_month = date('m');

        dd($this->expensesProvider->getAllOrderedByMainCategories($user->getId(), $current_year, $current_month));
    }
}
