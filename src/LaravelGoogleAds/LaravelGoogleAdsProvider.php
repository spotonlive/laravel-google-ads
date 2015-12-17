<?php

namespace LaravelGoogleAds;

use Illuminate\Support\ServiceProvider;

class LaravelGoogleAdsProvider extends ServiceProvider
{
    /**
     * Boot
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('google-ads.php'),
        ]);
    }

    /**
     * Register package
     */
    public function register()
    {
        $this->mergeConfig();

        // Console commands
        $this->commands([
            Console\GenerateRefreshTokenCommand::class,
        ]);
    }

    /**
     * Merge config
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php',
            'google-ads'
        );
    }
}