<?php

namespace App\Controller;

use App\Provider\Interfaces\ExpensesProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private $expensesProvider;

    public function __construct(ExpensesProviderInterface $expensesProvider)
    {
        $this->expensesProvider = $expensesProvider;
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

        return $this->render('main/index.html.twig', [
            'user' => $user,
            'expenses' => $this->expensesProvider->getLast($user->getId(),5),
            'current_year' => $current_year, 'current_month' => $current_month,
            'alert' => $alert, 'alert_class' => $alert_class,
        ]);
    }
}
