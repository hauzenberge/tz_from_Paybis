<?php

namespace App\Controller;

use App\Entity\CurrencyRate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class CurrencyApiController extends AbstractController
{
    #[Route('/currency/api', name: 'app_currency_api')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CurrencyApiController.php',
        ]);
    }

    #[Route("/api/exchange-rate", name: "api_exchange_rate")]
    public function getExchangeRate(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $currencyPairs = $request->get('pairs', []);

        // Validate the number of currency pairs
        if (count($currencyPairs) > 3) {
            return $this->json(['error' => 'Number of currency pairs should not exceed 3'], 400);
        }

        // Fetch exchange rates for the specified currency pairs from the database
        $exchangeRates = $this->fetchExchangeRatesFromDatabase($currencyPairs, $entityManager);

        return $this->json(['exchange_rates' => $exchangeRates]);
    }

    private function fetchExchangeRatesFromDatabase(array $currencyPairs, EntityManagerInterface $entityManager): array
    {
        $exchangeRates = [];

        foreach ($currencyPairs as $pair) {
            // Assuming $pair is in the format "BTC/USD"
            [$currency1, $currency2] = explode('/', $pair);

            // Fetch the exchange rate from the database
            $currencyRateEntity = $entityManager->getRepository(CurrencyRate::class)
                ->findOneBy(['currency1_name' => $currency1, 'currency2_name' => $currency2]);

            if ($currencyRateEntity) {
                // Format the exchange rate with 4 decimal places
                $exchangeRates[$pair] = number_format($currencyRateEntity->getRate(), 4);
            } else {
                // Handle the case where the exchange rate is not found
                $exchangeRates[$pair] = null;
            }
        }

        return $exchangeRates;
    }

    #[Route("/api/currency_pairs", name: "api_currencies")]
    public function getCurrencies(EntityManagerInterface $entityManager): JsonResponse
    {
        $currencyRepository = $entityManager->getRepository(CurrencyRate::class);
        $currencies = $currencyRepository->findAll();

        $currencyList = [];
        foreach ($currencies as $currency) {
            $currencyList[] = [
               $currency->getCurrency1Name(). '/' .$currency->getCurrency2Name(),
            ];
        }

        return $this->json(['currency_pairs' => $currencyList]);
    }
}
