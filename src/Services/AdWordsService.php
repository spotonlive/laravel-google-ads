<?php

namespace LaravelGoogleAds\Services;

use LaravelGoogleAds\AdWords\AdwordsUser;

class AdWordsService
{
    /**
     * Generate authorization url
     *
     * @param AdwordsUser $user
     * @return string
     * @throws \OAuth2Exception
     */
    public function getOAuthAuthorizationUrl(AdwordsUser $user)
    {
        $redirectUri = null;
        $offline = true;

        /** @var \SimpleOAuth2Handler $oAuth2Handler */
        $oAuth2Handler = $user->getOAuth2Handler();

        /*
         * Get the authorization URL for the OAuth2 token.
         * No redirect URL is being used since this is an installed application. A web
         * application would pass in a redirect URL back to the application,
         * ensuring it's one that has been configured in the API console.
         * Passing true for the second parameter ($offline) will provide us a refresh
         * token which can used be refresh the access token when it expires.
         */
        $authorizationUrl = $oAuth2Handler->GetAuthorizationUrl(
            $user->GetOAuth2Info(),
            $redirectUri,
            $offline
        );

        return $authorizationUrl;
    }

    /**
     * Get OAuth2 info
     *
     * @param AdwordsUser $user
     * @param string $accessCode
     * @param null $redirectUri
     * @return array
     * @throws \OAuth2Exception
     */
    public function getOAuthCredentials(AdwordsUser $user, $accessCode, $redirectUri = null)
    {
        /** @var \SimpleOAuth2Handler $oAuth2Handler */
        $oAuth2Handler = $user->getOAuth2Handler();

        $user->setOauth2Info(
            $oAuth2Handler->GetAccessToken(
                $user->getOauth2Info(),
                $accessCode,
                $redirectUri
            )
        );

        return $user->getOauth2Info();
    }
}
