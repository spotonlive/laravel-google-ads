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
     * @param null|string $customerClientId
     * @return AdsSoapClient|SoapClient
     */
    public function getService($serviceClass, $customerClientId = null)
    {
        $adwordsServices = new AdWordsServices();

        return $adwordsServices->get($this->session($customerClientId), $serviceClass);
    }

    /**
     * Create a new session
     *
     * @param null|string $customerClientId
     * @return AdWordsSession|mixed
     */
    public function session($customerClientId = null)
    {
        return ((new AdWordsSessionBuilder())
            ->from($this->configuration($customerClientId))
            ->withOAuth2Credential($this->oauth2Credentials($customerClientId))
            ->build());
    }

    /**
     * oAuth2 credentials
     * @param null|string $customerClientId
     * @return ServiceAccountCredentials|UserRefreshCredentials|mixed
     */
    private function oauth2Credentials($customerClientId = null)
    {
        return (new OAuth2TokenBuilder())
            ->from($this->configuration())
            ->build();
    }

    /**
     * Configuration
     *
     * @param string|null $customerClientId
     * @return Configuration
     */
    private function configuration($customerClientId = null)
    {
        $config = config('google-ads');

        if ($customerClientId) {
            $config['ADWORDS']['clientCustomerId'] = $customerClientId;
        }

        return new Configuration($config);
    }
}
