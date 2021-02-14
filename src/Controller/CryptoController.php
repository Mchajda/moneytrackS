<?php

namespace App\Controller;

use App\Provider\Interfaces\CryptoProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CryptoController extends AbstractController
{
    private $cryptoProvider;

    public function __construct
    (
        CryptoProviderInterface $cryptoProvider
    )
    {
        $this->cryptoProvider = $cryptoProvider;
    }

    /**
     * @Route("/crypto", name="crypto")
     */
    public function index(Request $request): Response
    {
        $alert = (string)$request->query->get('alert');
        $alert_class = (string)$request->query->get('alert_class');

        $crypto = $this->cryptoProvider->getCrypto();
        $dollar_value = 3.71;

        $current_year = date('Y');
        $current_month = date('m');

        return $this->render('crypto/index.html.twig', [
            'crypto' => $crypto,
            'balance' => $this->getUser()->getBalance(),
            'alert' => $alert, 'alert_class' => $alert_class,
            'dollar_value' => $dollar_value,
            'current_year' => $current_year, 'current_month' => $current_month,
        ]);
    }
}
