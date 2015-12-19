<?php

namespace App\Providers;

use App\Libraries\CacheHandler;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Interfaces\CacheHandlerInterface', function(){
            return new CacheHandler();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Interfaces\CacheHandlerInterface'];
    }
}