<?php


namespace App\Provider;


use App\Provider\Interfaces\CryptoProviderInterface;

class CryptoProvider implements CryptoProviderInterface
{
    public function getCrypto()
    {
        $crypto = [];

        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = [
            'start' => '1',
            'limit' => '10',
            'convert' => 'USD'
        ];

        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: 1a52a62d-d4c5-4499-828e-a40afcf1609b'
        ];
        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL


        $curl = curl_init(); // Get cURL resource
        // Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        //print_r(json_decode($response)->{'data'}[0]->{'quote'}->{'USD'}->{'price'}); // print json decoded response
        //print_r(json_decode($response)->{'data'}); // print json decoded response

        foreach(json_decode($response)->{'data'} as $d)
        {
            //echo $d->{'name'}.': '.$d->{'quote'}->{'USD'}->{'price'}.'USD </br>';
            $crypto[$d->{'name'}] = $d->{'quote'}->{'USD'}->{'price'};
        }

        curl_close($curl); // Close request
        return $crypto;
    }
}