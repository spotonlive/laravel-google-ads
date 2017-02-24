<p align="center">
<img src="https://cloud.githubusercontent.com/assets/3541622/17292148/47c841ea-57e8-11e6-80c3-773dfd28a1f4.png" alt="">
</p>

[![Laravel 5.1+](https://img.shields.io/badge/Laravel-5.1+-orange.svg?style=flat-square)](http://laravel.com) [![Lumen 5.1+](https://img.shields.io/badge/Lumen-5.1+-orange.svg?style=flat-square)](http://lumen.laravel.com) [![Latest Stable Version](https://poser.pugx.org/nikolajlovenhardt/laravel-google-ads/v/stable)](https://packagist.org/packages/nikolajlovenhardt/laravel-google-ads) [![Total Downloads](https://poser.pugx.org/nikolajlovenhardt/laravel-google-ads/downloads)](https://packagist.org/packages/nikolajlovenhardt/laravel-google-ads) [![Latest Unstable Version](https://poser.pugx.org/nikolajlovenhardt/laravel-google-ads/v/unstable)](https://packagist.org/packages/nikolajlovenhardt/laravel-google-ads) [![License](https://poser.pugx.org/nikolajlovenhardt/laravel-google-ads/license)](https://packagist.org/packages/nikolajlovenhardt/laravel-google-ads) [![Build Status](https://travis-ci.org/nikolajlovenhardt/laravel-google-ads.svg?branch=master)](https://travis-ci.org/nikolajlovenhardt/laravel-google-ads) [![Code Climate](https://codeclimate.com/github/nikolajlovenhardt/laravel-google-ads/badges/gpa.svg)](https://codeclimate.com/github/nikolajlovenhardt/laravel-google-ads) [![Test Coverage](https://codeclimate.com/github/nikolajlovenhardt/laravel-google-ads/badges/coverage.svg)](https://codeclimate.com/github/nikolajlovenhardt/laravel-google-ads/coverage)a

## Beta version

This project is still in beta version, and should not be used in a production environment. To-dos:
- [ ] Test coverage >90%
- [ ] Command to generate refresh token
- [x] Require namespaced version of [`googleads/googleads-php-lib`](https://github.com/googleads/googleads-php-lib)

## Google Ads API for Laravel

Integration of [`googleads/googleads-php-lib`](https://github.com/googleads/googleads-php-lib) in Laravel and Lumen (version >5).

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

use LaravelGoogleAds\Services\AdWordsService;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201609\cm\CampaignService;
use Google\AdsApi\AdWords\v201609\cm\OrderBy;
use Google\AdsApi\AdWords\v201609\cm\Paging;
use Google\AdsApi\AdWords\v201609\cm\Selector;

class Service
{
    /** @var AdWordsService */
    protected $adWordsService;

    public function foo()
    {
        $customerClientId = 'xxx-xxx-xx';

        $campaignService = $this->adWordsService->getService(CampaignService::class, $customerClientId);

        // Create selector.
        $selector = new Selector();
        $selector->setFields(array('Id', 'Name'));
        $selector->setOrdering(array(new OrderBy('Name', 'ASCENDING')));

        // Create paging controls.
        $selector->setPaging(new Paging(0, 100));

        // Make the get request.
        $page = $campaignService->get($selector);

        // Do something with the $page.
    }
}
```

### Best practices
[AdWords API Workshops Fall 2015](https://www.youtube.com/playlist?list=PLKByxjzUC-N8mEDQF9ARMMkSv0AmYbpsh)

### Features, requirements, support etc.
See [`googleads/googleads-php-lib`](https://github.com/googleads/googleads-php-lib/blob/master/README.md)

### Dependencies
- [`googleads/googleads-php-lib`](https://github.com/googleads/googleads-php-lib) hosts the PHP client library for the various SOAP-based Ads APIs (AdWords, AdExchange Buyer, and DFP) at Google.
