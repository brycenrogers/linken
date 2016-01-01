<?php

namespace App\Providers;

use App\Handlers\CacheHandler;
use Auth;
use Illuminate\Support\ServiceProvider;

/**
 * Class CacheManagerServiceProvider
 * @package App\Providers
 */
class CacheManagerServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Interfaces\UserCacheHandlerInterface', function(){
            return new CacheHandler(Auth::user());
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
            'App\Interfaces\CacheHandlerInterface',
            'App\Interfaces\UserCacheHandlerInterface'
        ];
    }
}