<?php

namespace LaravelGoogleAds\Services;

use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;

class AuthorizationService
{
    /**
     * @var string the Google OAuth2 authorization URI for OAuth2 requests
     * @see https://developers.google.com/identity/protocols/OAuth2InstalledApp#formingtheurl
     */
    const AUTHORIZATION_URI = 'https://accounts.google.com/o/oauth2/v2/auth';

    /**
     * @var string the OAuth2 scope for the AdWords API
     * @see https://developers.google.com/adwords/api/docs/guides/authentication#scope
     */
    const ADWORDS_API_SCOPE = 'https://www.googleapis.com/auth/adwords';

    /**
     * @var string the OAuth2 scope for the DFP API
     * @see https://developers.google.com/doubleclick-publishers/docs/authentication#scope
     */
    const DFP_API_SCOPE = 'https://www.googleapis.com/auth/dfp';

    /**
     * @var string the redirect URI for OAuth2 installed application flows
     * @see https://developers.google.com/identity/protocols/OAuth2InstalledApp#formingtheurl
     */
    const REDIRECT_URI = 'urn:ietf:wg:oauth:2.0:oob';

    /**
     * Fetch auth token
     *
     * @param OAuth2 $oAuth2
     * @param string $code
     * @return array
     */
    public function fetchAuthToken(Oauth2 $oAuth2, $code)
    {
        $oAuth2->setCode($code);

        return $oAuth2->fetchAuthToken();
    }

    /**
     * Build url
     *
     * @param OAuth2 $oAuth2
     * @param bool $offlineAccess
     * @param array $params
     * @return \Psr\Http\Message\UriInterface
     */
    public function buildFullAuthorizationUri(OAuth2 $oAuth2, $offlineAccess = false, $params = [])
    {
        $defaults = [];

        $params = array_merge(
            $defaults,
            $params
        );

        if ($offlineAccess) {
            $params['access_type'] = 'offline';
        }

        return $oAuth2->buildFullAuthorizationUri($params);
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string|null $redirectUri
     * @param string|null $scope
     * @return OAuth2
     */
    public function oauth2($clientId = null, $clientSecret = null, $redirectUri = null, $scope = null)
    {
        $credentials = $this->credentials();

        $clientId = ($clientId) ?: $credentials['clientId'];
        $clientSecret = ($clientSecret) ?: $credentials['clientSecret'];

        $scope = ($scope) ?: self::ADWORDS_API_SCOPE;
        $redirectUri = ($redirectUri) ?: self::REDIRECT_URI;

        return new OAuth2([
            'authorizationUri' => self::AUTHORIZATION_URI,
            'redirectUri' => $redirectUri,
            'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'scope' => $scope,
        ]);
    }

    /**
     * Credentials
     *
     * @return bool|mixed
     */
    private function credentials()
    {
        /** @var null|array $config */
        $config = config('google-ads');

        if (is_null($config) || !count($config)) {
            return false;
        }

        return array_merge([
            'clientId' => null,
            'clientSecret' => null,
        ], $config['OAUTH2']);
    }
}
