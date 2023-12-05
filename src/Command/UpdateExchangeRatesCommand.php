<?php

// src/Command/UpdateExchangeRatesCommand.php
namespace App\Command;

use App\Service\BinanceApiService;

use App\Entity\CurrencyRate;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateExchangeRatesCommand extends Command
{
    private $entityManager;
    private $binanceApiService;

    public function __construct(EntityManagerInterface $entityManager, BinanceApiService $binanceApiService)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->binanceApiService = $binanceApiService;
    }

    protected function configure()
    {
        $this->setName('app:update-exchange-rates')
            ->setDescription('Update exchange rates from external service');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $exchangeRates = $this->binanceApiService->getExchangeRates();

        foreach ($exchangeRates as $rate) {
            $currencySymbol = str_replace('USDT', '', $rate['symbol']); // Modify as needed
            $currencyRate = $rate['price'];

            $currencyEntity = $this->entityManager->getRepository(CurrencyRate::class)->findOneBy(['currency1_name' => $currencySymbol]);

            if (!$currencyEntity) {
                $currencyEntity = new CurrencyRate();
                $currencyEntity->setCurrency1Name($currencySymbol);
            }

            $currencyEntity->setCurrency2Name('USDT'); // Modify as needed
            $currencyEntity->setRate($currencyRate);
            $this->entityManager->persist($currencyEntity);
        }

        $this->entityManager->flush();

        $output->writeln('Exchange rates updated.');

        return Command::SUCCESS;
    }
}
