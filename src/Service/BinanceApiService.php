<?php
namespace App\Service;

use GuzzleHttp\Client;

class BinanceApiService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.binance.com',
        ]);
    }

    public function getExchangeRates(): array
    {
        $response = $this->client->get('/api/v3/ticker/price');
        $data = json_decode($response->getBody(), true);

        return $data;
    }
}
