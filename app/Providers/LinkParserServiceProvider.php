<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Libraries\LinkParserHandler;

/**
 * Class LinkParserServiceProvider
 * @package App\Providers
 */
class LinkParserServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Interfaces\LinkParserInterface', function() {
            return new LinkParserHandler();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'App\Interfaces\LinkParserInterface'
        ];
    }
}
