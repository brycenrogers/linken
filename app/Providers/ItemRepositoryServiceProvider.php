<?php

namespace App\Providers;

use App\Models\Item,
    Illuminate\Support\ServiceProvider,
    Auth,
    App\Repositories\ItemRepository;

/**
 * Class ItemRepositoryServiceProvider
 * @package App\Providers
 */
class ItemRepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Interfaces\ItemRepositoryInterface', function() {
            return new ItemRepository(new Item(), Auth::user());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Interfaces\ItemRepositoryInterface'];
    }
}
