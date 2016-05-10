<p align="center">
<img src="https://cloud.githubusercontent.com/assets/3541622/11866448/7da4660e-a4ab-11e5-9ef1-941342e177bb.png" alt="">
</p>

[![Laravel 5.1+](https://img.shields.io/badge/Laravel-5.1+-orange.svg?style=flat-square)](http://laravel.com) [![Lumen 5.1+](https://img.shields.io/badge/Lumen-5.1+-orange.svg?style=flat-square)](http://lumen.laravel.com) [![Latest Stable Version](https://poser.pugx.org/nikolajlovenhardt/laravel-google-ads/v/stable)](https://packagist.org/packages/nikolajlovenhardt/laravel-google-ads) [![Total Downloads](https://poser.pugx.org/nikolajlovenhardt/laravel-google-ads/downloads)](https://packagist.org/packages/nikolajlovenhardt/laravel-google-ads) [![Latest Unstable Version](https://poser.pugx.org/nikolajlovenhardt/laravel-google-ads/v/unstable)](https://packagist.org/packages/nikolajlovenhardt/laravel-google-ads) [![License](https://poser.pugx.org/nikolajlovenhardt/laravel-google-ads/license)](https://packagist.org/packages/nikolajlovenhardt/laravel-google-ads) [![Build Status](https://travis-ci.org/nikolajlovenhardt/laravel-google-ads.svg?branch=master)](https://travis-ci.org/nikolajlovenhardt/laravel-google-ads) [![Code Climate](https://codeclimate.com/github/nikolajlovenhardt/laravel-google-ads/badges/gpa.svg)](https://codeclimate.com/github/nikolajlovenhardt/laravel-google-ads) [![Test Coverage](https://codeclimate.com/github/nikolajlovenhardt/laravel-google-ads/badges/coverage.svg)](https://codeclimate.com/github/nikolajlovenhardt/laravel-google-ads/coverage)

## Google Ads API for Laravel

This project is an integration of [`googleads/googleads-php-lib`](https://github.com/googleads/googleads-php-lib) in Laravel and Lumen supporting 5.1 and 5.2.

### Setup
- Run `$ composer require nikolajlovenhardt/laravel-google-ads`

#### Laravel

- Add provider to config/app.php

```php
'providers' => [
    LaravelGoogleAds\LaravelGoogleAdsProvider::class,
],
```

- Run `$ php artisan vendor:publish` to publish the configuration file `config/google-ads.php` and insert:
    - developerToken
    - clientId & clientSecret
    - refreshToken

#### Lumen

- Add provider to `bootstrap/app.php`

```php
$app->register(LaravelGoogleAds\LaravelGoogleAdsProvider::class);
```

- Copy `vendor/nikolajlovenhardt/laravel-google-ads/config/config.php` to `config/google-ads.php` and insert:
    - developerToken
    - clientId & clientSecret
    - refreshToken

- Add config to `bootstrap/app.php`

```php
$app->configure('google-ads');
```

### Generate refresh token
*This requires that the `clientId` and `clientSecret` is from a native application.*

Run `$ php artisan googleads:token:generate` and open the authorization url. Grant access to the app, and input the
access token in the console. Copy the refresh token into your configuration `config/google-ads.php`

### Basic usage

The following example is for AdWords, but the general code applies to all
products.


```php
<?php

namespace App\Services;

use Money;
use Budget;
use Campaign;
use CampaignService;
use CampaignOperation;
use BiddingStrategyConfiguration;
use LaravelGoogleAds\AdWords\AdWordsUser;

class Service
{
    public function foo()
    {
        $user = new AdWordsUser();

        // Optionally, enable logging to capture the content of SOAP requests and responses.
        $user->LogDefaults();

        /*
         * Instantiate the desired service class by calling $user->GetService([SERVICE], [VERSION])
         * Example:
         */

        /** @var CampaignService $campaignService */
        $campaignService = $user->GetService('CampaignService', 'v201603');

        /*
         * Create data objects and invoke methods on the service class instance. The
         * data objects and methods map directly to the data objects and requests for
         * the corresponding web service.
         */

        // Create new campaign structure
        $campaign = new Campaign();
        $campaign->name = 'Campaign #' . time();
        $campaign->status = 'ACTIVE';
        $campaign->biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $campaign->biddingStrategyConfiguration->biddingStrategyType = 'MANUAL_CPC';
        $campaign->budget = new Budget('DAILY', new Money(50000000), 'STANDARD');

        $operation = new CampaignOperation();
        $operation->operand = $campaign;
        $operation->operator = 'ADD';
        $operations[] = $operation;

        // Add campaign
        $campaignReturnValue = $campaignService->mutate($operations);
    }

    public function bar()
    {
        // Create an AdWordsUser instance using the default constructor
        $user = new AdWordsUser();
        $user->SetClientCustomerId('INSERT_CLIENT_CUSTOMER_ID_HERE');
    }
}
```

### Best practices
[AdWords API Workshops Fall 2015](https://www.youtube.com/playlist?list=PLKByxjzUC-N8mEDQF9ARMMkSv0AmYbpsh)

### Features, requirements, support etc.
See [`googleads/googleads-php-lib`](https://github.com/googleads/googleads-php-lib/blob/master/README.md)

### Dependencies
- [`googleads/googleads-php-lib`](https://github.com/googleads/googleads-php-lib) hosts the PHP client library for the various SOAP-based Ads APIs (AdWords, AdExchange Buyer, and DFP) at Google.
