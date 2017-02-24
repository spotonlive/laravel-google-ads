<?php

namespace LaravelGoogleAds\Services;

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\Common\AdsSoapClient;
use Google\AdsApi\Common\Configuration;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\Common\SoapClient;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\Credentials\UserRefreshCredentials;

class AdWordsService
{
    /**
     * Get service
     *
     * @param string $serviceClass
     * @param null|string $clientCustomerId
     * @return AdsSoapClient|SoapClient
     */
    public function getService($serviceClass, $clientCustomerId = null)
    {
        $adwordsServices = new AdWordsServices();

        return $adwordsServices->get($this->session($clientCustomerId), $serviceClass);
    }

    /**
     * Create a new session
     *
     * @param null|string $clientCustomerId
     * @return AdWordsSession|mixed
     */
    public function session($clientCustomerId = null)
    {
        return ((new AdWordsSessionBuilder())
            ->from($this->configuration($clientCustomerId))
            ->withOAuth2Credential($this->oauth2Credentials($clientCustomerId))
            ->build());
    }

    /**
     * oAuth2 credentials
     * @param null|string $clientCustomerId
     * @return ServiceAccountCredentials|UserRefreshCredentials|mixed
     */
    private function oauth2Credentials($clientCustomerId = null)
    {
        return (new OAuth2TokenBuilder())
            ->from($this->configuration())
            ->build();
    }

    /**
     * Configuration
     *
     * @param string|null $clientCustomerId
     * @return Configuration
     */
    private function configuration($clientCustomerId = null)
    {
        $config = config('google-ads');

        if ($clientCustomerId) {
            $config['ADWORDS']['clientCustomerId'] = $clientCustomerId;
        }

        return new Configuration($config);
    }
}
