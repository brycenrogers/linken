<?php

namespace App\Providers;

use App\Handlers\SearchHandler;
use Illuminate\Support\ServiceProvider;
use Auth;

/**
 * Class SearchHandlerServiceProvider
 * @package App\Providers
 */
class SearchHandlerServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Interfaces\UserSearchHandlerInterface', function() {
            return new SearchHandler(Auth::user());
        });
        $this->app->bind('App\Interfaces\SearchHandlerInterface', function() {
            return new SearchHandler();
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
            'App\Interfaces\SearchHandlerInterface',
            'App\Interfaces\UserSearchHandlerInterface'
        ];
    }
}
