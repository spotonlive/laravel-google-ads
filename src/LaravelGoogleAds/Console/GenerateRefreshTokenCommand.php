<?php

namespace LaravelGoogleAds\Console;

use Exception;
use Illuminate\Console\Command;
use LaravelGoogleAds\Services\AuthorizationService;

class GenerateRefreshTokenCommand extends Command
{
    /** @var string */
    protected $signature = 'googleads:token:generate';

    /** @var string */
    protected $description = 'Generate a new refresh token for Google Ads API';

    /** @var AuthorizationService */
    protected $authorizationService;

    /**
     * GenerateRefreshTokenCommand constructor.
     * @param AuthorizationService $authorizationService
     */
    public function __construct(AuthorizationService $authorizationService)
    {
        parent::__construct();

        $this->authorizationService = $authorizationService;
    }

    /**
     * Execute the console command.
     *
     * @version Laravel 5.4+
     */
    public function handle()
    {
        $this->fire();
    }

    /**
     * Generate command
     */
    public function fire()
    {
        $authorizationService = $this->authorizationService;

        if (!$config = $this->config()) {
            $this->error('Please provide a valid configuration for Laravel Google Ads');

            return;
        }

        $clientId = $config['clientId'];
        $clientSecret = $config['clientSecret'];

        // Scopes

        $scopes = $this->scopes();

        $oauth2 = $authorizationService->oauth2(
            $clientId,
            $clientSecret,
            AuthorizationService::REDIRECT_URI,
            implode(" ", $scopes)
        );

        $this->line(sprintf(
            "Please sign in to your Google Account, and navigate to the following url:\n%s",
            $authorizationService->buildFullAuthorizationUri($oauth2, true, [
                'prompt' => 'consent',
            ])
        ));

        // Retrieve token

        $accessToken = $this->ask('Insert the code you received from Google');

        // Fetch auth token

        try {
            $authToken = $authorizationService->fetchAuthToken($oauth2, $accessToken);
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
     * Select scopes
     *
     * @return array
     */
    private function scopes()
    {
        $scopes = [];

        foreach ($this->products() as $product => $scope) {
            if (!$this->confirm(sprintf('Would you like to activate %s?', $product), true)) {
                continue;
            }

            array_push($scopes, $scope);
        }

        if (!count($scopes)) {
            $this->error('You have to select at least one scope');

            return $this->scopes();
        }

        return $scopes;
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

    /**
     * Products
     *
     * @return array
     */
    private function products()
    {
        return [
            'AdWords' => AuthorizationService::ADWORDS_API_SCOPE,
            'DFP' => AuthorizationService::DFP_API_SCOPE,
        ];
    }
}
