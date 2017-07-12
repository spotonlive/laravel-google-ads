<?php

namespace LaravelGoogleAds\Services;

use Google\AdsApi\Dfp\DfpServices;
use Google\AdsApi\Dfp\DfpSessionBuilder;
use Google\AdsApi\Common\AdsSoapClient;
use Google\AdsApi\Common\Configuration;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\Common\SoapClient;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\Credentials\UserRefreshCredentials;

class DfpService
{
    /**
     * Get service.
     *
     * @param string $serviceClass
     *
     * @return AdsSoapClient|SoapClient
     */
    public function getService($serviceClass)
    {
        $dfpServices = new DfpServices();

        return $dfpServices->get($this->session(), $serviceClass);
    }

    /**
     * Create a new session.
     *
     * @return AdWordsSession|mixed
     */
    public function session()
    {
        $session = (new DfpSessionBuilder())
            ->from($this->configuration())
            ->withOAuth2Credential($this->oauth2Credentials())
            ->build();

        return (new DfpSessionBuilder())
            ->from($this->configuration())
            ->withOAuth2Credential($this->oauth2Credentials())
            ->build();
    }

    /**
     * oAuth2 credentials.
     *
     * @return ServiceAccountCredentials|UserRefreshCredentials|mixed
     */
    private function oauth2Credentials()
    {
        return (new OAuth2TokenBuilder())
            ->from($this->configuration())
            ->build();
    }

    /**
     * Configuration.
     *
     * @return Configuration
     */
    private function configuration()
    {
        $config = config('google-ads');

        return new Configuration($config);
    }
}
