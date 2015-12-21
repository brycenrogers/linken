<?php

namespace App\Providers;

use App\Libraries\ImageHandler;
use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Interfaces\ImageHandlerInterface', function(){
            return new ImageHandler();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Interfaces\ImageHandlerInterface'];
    }
}
