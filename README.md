## Google Ads API for Laravel

## THIS PACKAGE IS UNDER DEVELOPMENT, AND SHOULD NOT BE USED IN PRODUCTION ENVIRONMENT

This project hosts the PHP client library for the various SOAP-based Ads APIs (AdWords, AdExchange Buyer, and DFP) at Google.

### Features, requirements, support etc.
See [`googleads/googleads-php-lib`](https://github.com/googleads/googleads-php-lib/blob/master)

### Setup
- Run `$ composer require nikolajlovenhardt/laravel-google-ads`

- Add provider
```php
'providers' => [
    LaravelGoogleAds\LaravelGoogleAdsProvider::class,
],
```

- Run `$ php artisan vendor:publish` to publish the configuration file `config/google-ads.php` and insert:
    - developerToken
    - clientId & clientSecret
    - refreshToken

### Generate refresh token
*This requires that the `clientId` and `clientSecret` is from a native application.*

Run `$ php artisan googleads:token:generate` and open the authorization url. Grant access to the app, and input the
access token in the console. Copy the refresh token into your configuration `config/google-ads.php`

### Basic usage

The following example is for AdWords, but the general code applies to all
products.


```php
use Campaign;
use CampaignService;
use CampaignOperation;
use LaravelGoogleAds\AdWords\AdWordsUser;

class Service
{
    public function foo()
    {
        $user = new AdWordsUser();

        // Optionally, enable logging to capture the content of SOAP requests and responses.
        $user->LogDefaults();

        // Instantiate the desired service class by calling the get***Service method on the AdWordsUser instance.
        /** @var CampaignService $campaignService */
        $campaignService = $user->GetService('CampaignService', 'v201509');

        // Create data objects and invoke methods on the service class instance. The
        // data objects and methods map directly to the data objects and requests for
        // the corresponding web service.


        // Create new campaign structure.
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

        // Add campaign.
        $campaignReturnValue = $campaignService->mutate($operations)
    }

    public function bar()
    {
        // Create an AdWordsUser instance using the default constructor
        $user = new AdWordsUser();
        $user->SetClientCustomerId('INSERT_CLIENT_CUSTOMER_ID_HERE');
    }
};
```

### Dependencies
See [`googleads/googleads-php-lib`](https://github.com/googleads/googleads-php-lib/blob/master/README.md)