<?php

namespace App\Controller;

use App\Provider\Interfaces\InflationProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InflationController extends AbstractController
{
    private $inflationProvider;

    public function __construct
    (
        InflationProviderInterface $inflationProvider
    )
    {
        $this->inflationProvider = $inflationProvider;
    }

    /**
     * @Route("/inflation", name="inflation")
     */
    public function index(Request $request): Response
    {
        $alert = (string)$request->query->get('alert');
        $alert_class = (string)$request->query->get('alert_class');

        return $this->render('inflation/index.html.twig', [
            'controller_name' => 'InflationController',
            'balance' => $this->getUser()->getBalance(),
            'alert' => $alert, 'alert_class' => $alert_class,
            'inflation' => $this->inflationProvider->getAll(),
        ]);
    }
}
