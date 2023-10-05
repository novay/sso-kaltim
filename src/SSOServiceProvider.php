<?php

namespace Novay\SSOKaltim;

use Illuminate\Support\ServiceProvider;

class SSOServiceProvider extends ServiceProvider
{   
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        
        $this->registerConfig();
        $this->configurePublishing();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // 
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sso-kaltim.php', 'sso-kaltim');

        $this->publishes([__DIR__.'/../config' => config_path()], 'sso-config');
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/Database/migrations/2023_09_26_100536_create_oauths_table.php' => database_path('migrations/2023_09_26_100536_create_oauths_table.php')
        ], 'sso-migrations');
    }
}