<?php

namespace LaravelGoogleAds\Console;

use Exception;
use Illuminate\Console\Command;
use LaravelGoogleAds\AdWords\AdWordsUser;
use LaravelGoogleAds\Services\AdWordsService;
use OAuth2Exception;

class GenerateRefreshTokenCommand extends Command
{
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

    /**
     * Generate refresh token
     *
     * @param AdWordsService $adWordsService
     */
    public function fire(AdWordsService $adWordsService)
    {
        $user = new AdWordsUser();
        $authorizationUrl = $adWordsService->getOAuthAuthorizationUrl($user);

        // Post authorization URL
        $this->line(sprintf(
            "Please sign in to your AdWords account, and open following url:\n%s",
            $authorizationUrl
        ));

        // Retrieve token
        $accessToken = $this->ask('Insert your access token:');

        try {
            $oAuth2Info = $adWordsService->getOAuthCredentials($user, $accessToken);
        } catch (OAuth2Exception $e) {
            // OAuth2 error
            return $this->error('[OAUTH2]: ' . $e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }

        if (!isset($oAuth2Info['refresh_token'])) {
            return $this->error('Error fetching the refresh token');
        }

        $this->comment('Insert the refresh token in your googleads configuration file (config/google-ads.php)');

        // Print refresh token
        $this->line(sprintf(
            'Refresh token: "%s"',
            $oAuth2Info['refresh_token']
        ));
    }
}
