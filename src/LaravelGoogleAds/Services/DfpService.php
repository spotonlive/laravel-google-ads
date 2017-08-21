<?php

namespace LaravelGoogleAds\Services;

use Google\AdsApi\Dfp\DfpServices;
use Google\AdsApi\Common\AdsSoapClient;
use Google\AdsApi\Common\Configuration;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\Dfp\DfpSession;
use Google\AdsApi\Dfp\DfpSessionBuilder;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\Credentials\UserRefreshCredentials;

class DfpService
{
    /**
     * Get service
     *
     * @param string $serviceClass
     * @return AdsSoapClient
     */
    public function getService($serviceClass)
    {
        $dfpServices = new DfpServices();

        $session = $this->session();

        return $dfpServices->get($session, $serviceClass);
    }

    /**
     * Create a new session
     *
     * @return DfpSession|mixed
     */
    public function session()
    {
        return ((new DfpSessionBuilder())
            ->from($this->configuration())
            ->withOAuth2Credential($this->oauth2Credentials())
            ->build());
    }

    /**
     * oAuth2 credentials
     * @return ServiceAccountCredentials|UserRefreshCredentials|mixed
     */
    private function oauth2Credentials()
    {
        return (new OAuth2TokenBuilder())
            ->from($this->configuration())
            ->build();
    }

    /**
     * Configuration
     *
     * @return Configuration
     */
    private function configuration()
    {
        $config = config('google-ads');

        return new Configuration($config);
    }
}
