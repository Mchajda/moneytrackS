<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SummaryController extends AbstractController
{
    /**
     * @Route("/summary/{year}/{month}", name="summary")
     */
    public function index($year, $month): Response
    {
        $alert = "";
        $alert_class = "";

        return $this->render('summary/index.html.twig', [
            'current_year' => $year, 'current_month' => $month,
            'alert' => $alert, 'alert_class' => $alert_class,
        ]);
    }
}
