<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider,
    Auth,
    App\Libraries\CacheHandler,
    App\Models\Item,
    App\Repositories\ItemRepository,
    App\Models\Note,
    App\Repositories\NoteRepository,
    App\Models\Link,
    App\Repositories\LinkRepository,
    App\Models\Tag,
    App\Repositories\TagRepository,
    App\Libraries\ItemHandler;

/**
 * Class ItemHandlerServiceProvider
 * @package App\Providers
 */
class ItemHandlerServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Interfaces\ItemHandlerInterface', function() {
            $user = Auth::user();
            $cache = new CacheHandler($user);
            $items = new ItemRepository(new Item(), $user);
            $links = new LinkRepository(new Link(), $user);
            $notes = new NoteRepository(new Note(), $user);
            $tags  = new TagRepository(new Tag(), $user);
            return new ItemHandler($cache, $items, $links, $notes, $tags, $user);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Interfaces\ItemHandlerInterface'];
    }
}
