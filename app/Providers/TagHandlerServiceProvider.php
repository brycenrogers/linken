<?php

namespace App\Providers;

use App\Handlers\TagHandler;
use Illuminate\Support\ServiceProvider;

/**
 * Class TagHandlerServiceProvider
 * @package App\Providers
 */
class TagHandlerServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Interfaces\TagHandlerInterface', function() {
            return new TagHandler();
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
            'App\Interfaces\TagHandlerInterface'
        ];
    }
}
