## Google Ads API For laravel

## THIS PACKAGE IS UNDER DEVELOPMENT, AND SHOULD NOT BE USED IN PRODUCTION ENVIRONMENT

This project hosts the PHP client library for the various SOAP-based Ads APIs
(AdWords, AdExchange Buyer, and DFP) at Google.

### Features, requirements, support etc.
See [`googleads/googleads-php-lib`](https://github.com/googleads/googleads-php-lib/blob/master/README.md)

### Basic usage

The following example is for AdWords, but the general code applies to all
products.


```php

// Set the include path and the require the folowing PHP file.
//
// You can set the include path to src directory or reference
// AdWordsUser.php directly via require_once.
// $path = '/path/to/pda_api_php_lib/src';
$path = dirname(__FILE__) . '/../../../src';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'Google/Api/Ads/AdWords/Lib/AdWordsUser.php';

// Create an AdWordsUser instance using the default constructor, which will load
// information from the auth.ini file as described above.
$user = new \LaravelGoogleAds\AdWordsUser();

// Optionally, enable logging to capture the content of SOAP requests and
// responses.
$user->LogDefaults();

// Instantiate the desired service class by calling the get***Service method on
// the AdWordsUser instance.
$campaignService = $user->GetService('CampaignService', 'v201309');

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
$campaignReturnValue = $campaignService->mutate($operations);
```

### How do I set different client customer IDs than specified in auth.ini?

You can do this by calling `SetClientCustomerId()` of an `AdWordUser` object
with a parameter as client customer ID you want to set to:

```php
// Create an AdWordsUser instance using the default constructor, which will load
// information from the auth.ini file as described above.
$user = new AdWordsUser();
$user->SetClientCustomerId('INSERT_CLIENT_CUSTOMER_ID_HERE');
```

``` Dependencies
See [`googleads/googleads-php-lib`](https://github.com/googleads/googleads-php-lib/blob/master/README.md)