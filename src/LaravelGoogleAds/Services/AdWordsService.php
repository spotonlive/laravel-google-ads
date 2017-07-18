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
use Google\Auth\FetchAuthTokenInterface;

class AdWordsService
{
    /**
     * Get service
     *
     * @param string $serviceClass
     * @param string|null $clientCustomerId
     * @param string|null $credentials
     * @return AdsSoapClient|SoapClient
     */
    public function getService($serviceClass, $clientCustomerId = null, $credentials = null)
    {
        $adwordsServices = new AdWordsServices();

        $session = $this->session($clientCustomerId, $credentials);

        return $adwordsServices->get($session, $serviceClass);
    }

    /**
     * Create a new session
     *
     * @param null|string $clientCustomerId
     * @param string|null $credentials
     * @return AdWordsSession|mixed
     */
    public function session($clientCustomerId = null, $credentials = null)
    {
        $credentials = (is_null($credentials)) ? $this->oauth2Credentials($clientCustomerId) : $credentials;

        return ((new AdWordsSessionBuilder())
            ->from($this->configuration($clientCustomerId))
            ->withOAuth2Credential($credentials)
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

        if (!is_null($clientCustomerId)) {
            $config['ADWORDS']['clientCustomerId'] = $clientCustomerId;
        }

        return new Configuration($config);
    }
}
