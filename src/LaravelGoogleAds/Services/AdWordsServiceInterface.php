<?php

namespace LaravelGoogleAds\Services;

use LaravelGoogleAds\AdWords\AdwordsUser;

interface AdWordsServiceInterface
{
    /**
     * Generate authorization url
     *
     * @param AdwordsUser $user
     * @return string
     * @throws \OAuth2Exception
     */
    public function getOAuthAuthorizationUrl(AdwordsUser $user);

    /**
     * Get OAuth2 info
     *
     * @param AdwordsUser $user
     * @param string $accessCode
     * @param null $redirectUri
     * @return array
     * @throws \OAuth2Exception
     */
    public function getOAuthCredentials(AdwordsUser $user, $accessCode, $redirectUri = null);
}
