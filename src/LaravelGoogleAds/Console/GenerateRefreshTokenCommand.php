<?php

namespace LaravelGoogleAds\Console;

use Exception;
use Google\Auth\OAuth2;
use Illuminate\Console\Command;
use Google\Auth\CredentialsLoader;

class GenerateRefreshTokenCommand extends Command
{
    /**
     * @var string the OAuth2 scope for the AdWords API
     *
     * @see https://developers.google.com/adwords/api/docs/guides/authentication#scope
     */
    const ADWORDS_API_SCOPE = 'https://www.googleapis.com/auth/adwords';

    /**
     * @var string the OAuth2 scope for the DFP API
     *
     * @see https://developers.google.com/doubleclick-publishers/docs/authentication#scope
     */
    const DFP_API_SCOPE = 'https://www.googleapis.com/auth/dfp';

    /**
     * @var string the Google OAuth2 authorization URI for OAuth2 requests
     *
     * @see https://developers.google.com/identity/protocols/OAuth2InstalledApp#formingtheurl
     */
    const AUTHORIZATION_URI = 'https://accounts.google.com/o/oauth2/v2/auth';

    /**
     * @var string the redirect URI for OAuth2 installed application flows
     *
     * @see https://developers.google.com/identity/protocols/OAuth2InstalledApp#formingtheurl
     */
    const REDIRECT_URI = 'urn:ietf:wg:oauth:2.0:oob';

    /**
     * Console command signature.
     *
     * @var string
     */
    protected $signature = 'googleads:token:generate';

    /**
     * Description.
     *
     * @var string
     */
    protected $description = 'Generate a new refresh token for Google Ads API';

    /**
     * Generate command.
     */
    public function fire()
    {
        if (!$config = $this->config()) {
            return $this->error('Please provide a valid configuration for Laravel Google Ads');
        }

        $products = [
            ['AdWords', self::ADWORDS_API_SCOPE],
            ['DFP', self::DFP_API_SCOPE],
            ['AdWords and DFP', self::ADWORDS_API_SCOPE.' '.self::DFP_API_SCOPE],
        ];

        $api = $this->ask("Select the ads API you\'re using: \n [0] AdWords \n [1] DFP \n [2] Both");
        if ($api === 2) {
            $this->info('[OPTIONAL] enter any additional OAuth2 scopes as a space '
                .'delimited string here (the AdWords and DFP scopes are already '
                .'included): ');
        } else {
            $this->info('[OPTIONAL] enter any additional OAuth2 scopes as a space '
                .'delimited string here (the '.$products[$api][0].' scope is already included): ');
        }

        $clientId = $config['clientId'];
        $clientSecret = $config['clientSecret'];
        $scopes = $products[$api][1];

        $oauth2 = new OAuth2([
            'authorizationUri' => self::AUTHORIZATION_URI,
            'redirectUri' => self::REDIRECT_URI,
            'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'scope' => $scopes,
        ]);

        $this->line(sprintf(
            'Log into the Google account you use for %s and visit the following '
           ."URL:\n%s\n\n",
           $products[$api][0],
           $oauth2->buildFullAuthorizationUri()
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
     * Configuration.
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
