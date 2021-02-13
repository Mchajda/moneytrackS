<?php

namespace App\Controller;

use App\Provider\Interfaces\InflationProviderInterface;
use App\Service\Interfaces\InflationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InflationController extends AbstractController
{
    private $inflationProvider;
    private $inflationService;

    public function __construct
    (
        InflationProviderInterface $inflationProvider,
        InflationServiceInterface $inflationService
    )
    {
        $this->inflationProvider = $inflationProvider;
        $this->inflationService = $inflationService;
    }

    /**
     * @Route("/inflation", name="inflation")
     */
    public function index(Request $request): Response
    {
        $alert = (string)$request->query->get('alert');
        $alert_class = (string)$request->query->get('alert_class');

        $this_year_value = $this->inflationProvider->getAll()[count($this->inflationProvider->getAll())-1];
        $inflation_values = $this->inflationProvider->getAllValues();
        $inflation_years = $this->inflationProvider->getAllYears();

        $budget_converted = $this->inflationService->budgetOnInflation($inflation_values, $this->getUser()->getBalance());

        return $this->render('inflation/index.html.twig', [
            'current_year' => date('Y'), 'current_month' => date('m'),
            'user' => $this->getUser(),
            'alert' => $alert, 'alert_class' => $alert_class,
            'inflation' => $this->inflationProvider->getAll(),
            'this_year_value' => $this_year_value->getValue(),
            'inflation_years' => $inflation_years, 'budget_converted' => $budget_converted,
        ]);
    }
}
