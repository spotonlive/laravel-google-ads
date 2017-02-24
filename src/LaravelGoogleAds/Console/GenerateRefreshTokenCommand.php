<?php

namespace LaravelGoogleAds\Console;

use Exception;
use Google\Auth\OAuth2;
use Illuminate\Console\Command;
use Google\Auth\CredentialsLoader;
use LaravelGoogleAds\Services\AdWordsService;

class GenerateRefreshTokenCommand extends Command
{
    /**
     * @var string the OAuth2 scope for the AdWords API
     * @see https://developers.google.com/adwords/api/docs/guides/authentication#scope
     */
    const ADWORDS_API_SCOPE = 'https://www.googleapis.com/auth/adwords';

    /**
     * @var string the Google OAuth2 authorization URI for OAuth2 requests
     * @see https://developers.google.com/identity/protocols/OAuth2InstalledApp#formingtheurl
     */
    const AUTHORIZATION_URI = 'https://accounts.google.com/o/oauth2/v2/auth';

    /**
     * @var string the redirect URI for OAuth2 installed application flows
     * @see https://developers.google.com/identity/protocols/OAuth2InstalledApp#formingtheurl
     */
    const REDIRECT_URI = 'urn:ietf:wg:oauth:2.0:oob';

    /**
     * Console command signature
     *
     * @var string
     */
    protected $signature = 'googleads:token:generate';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Generate a new refresh token for Google Ads API';

    public function fire()
    {
        if (!$config = $this->config()) {
            return $this->error('Please provide a valid configuration for Laravel Google Ads');
        }

        $clientId = $config['clientId'];
        $clientSecret = $config['clientSecret'];
        $scopes = self::ADWORDS_API_SCOPE;

        $oauth2 = new OAuth2([
            'authorizationUri' => self::AUTHORIZATION_URI,
            'redirectUri' => self::REDIRECT_URI,
            'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'scope' => $scopes
        ]);

        $this->line(sprintf(
            "Please sign in to your AdWords account, and open following url:\n%s",
            $oauth2->buildFullAuthorizationUri([
                'access_type' => 'offline',
            ])
        ));

        // Retrieve token
        $accessToken = $this->ask('Insert your access token');

        // Fetch auth token
        try {
            $oauth2->setCode($accessToken);
            $authToken = $oauth2->fetchAuthToken();
        } catch (Exception $exception) {
            return $this->error($exception->getMessage());
        }

        if (!isset($authToken)) {
            return $this->error('Error fetching the refresh token');
        }

        $this->comment('Insert the refresh token in your googleads configuration file (config/google-ads.php)');

        // Print refresh token
        $this->line(sprintf(
            'Refresh token: "%s"',
            $authToken['refresh_token']
        ));
    }

    /**
     * Configuration
     *
     * @return bool|array
     */
    private function config()
    {
        /** @var null|array $config */
        $config = config('google-ads');

        if (is_null($config) || !count($config)) {
            return false;
        }

        return $config['OAUTH2'];
    }
}
