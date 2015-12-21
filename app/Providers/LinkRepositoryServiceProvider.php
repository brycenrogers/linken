<?php

namespace App\Providers;

use App\Models\Link;
use Illuminate\Support\ServiceProvider;
use Auth;
use App\Repositories\LinkRepository;

/**
 * Class LinkRepositoryServiceProvider
 * @package App\Providers
 */
class LinkRepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Interfaces\UserLinkRepositoryInterface', function() {
            return new LinkRepository(new Link(), Auth::user());
        });
        $this->app->bind('App\Interfaces\LinkRepositoryInterface', function() {
            return new LinkRepository(new Link());
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
            'App\Interfaces\LinkRepositoryInterface',
            'App\Interfaces\UserLinkRepositoryInterface'
        ];
    }
}
