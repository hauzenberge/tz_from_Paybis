# Currency Exchange API

This Symfony-based project provides a simple API for fetching currency exchange rates and managing currency pairs.

## Getting Started

### Prerequisites

Before you begin, ensure you have the following installed:

- PHP
- Composer
- Symfony CLI
- MySQL (or another compatible database)

### Installation

1. Clone the repository: git clone git@github.com:hauzenberge/tz_from_Paybis.git

2. Navigate to the project directory: cd tz_from_Paybis

3.Install dependencies: composer install

4.  Open the .env file in the root of your project and update the following lines with your database connection details: DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

5. Set up the database:
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

## Usage

Fetching Exchange Rates
To get the exchange rates for specific currency pairs, use the /api/exchange-rate endpoint. You can provide up to three currency pairs as parameters.

Example: curl -X GET "http://your-symfony-app/api/exchange-rate?pairs[]=BTC/USD&pairs[]=BTC/EUR"

Listing Available Currency Pairs
To retrieve a list of available currency pairs in the database, use the /api/currency_pairs endpoint.

Example: curl -X GET "http://your-symfony-app/api/currency_pairs"

## Updating Exchange Rates

To keep the exchange rates up to date, add the following command to your task scheduler (e.g., cron): php bin/console app:update-exchange-rate
This command will fetch and update the exchange rates from an external source.
