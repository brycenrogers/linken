<?php

namespace App\Providers;

use App\Models\Tag;
use Illuminate\Support\ServiceProvider;
use Auth;
use App\Repositories\TagRepository;

/**
 * Class TagRepositoryServiceProvider
 * @package App\Providers
 */
class TagRepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Interfaces\UserTagRepositoryInterface', function() {
            return new TagRepository(new Tag(), Auth::user());
        });
        $this->app->bind('App\Interfaces\TagRepositoryInterface', function() {
            return new TagRepository(new Tag());
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
            'App\Interfaces\TagRepositoryInterface',
            'App\Interfaces\UserTagRepositoryInterface'
        ];
    }
}
