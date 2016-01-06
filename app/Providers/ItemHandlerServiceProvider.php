<?php

namespace App\Providers;

use App\Handlers\SearchHandler;
use Illuminate\Support\ServiceProvider;
use Auth;
use App\Handlers\CacheHandler;
use App\Models\Item;
use App\Repositories\ItemRepository;
use App\Models\Note;
use App\Repositories\NoteRepository;
use App\Models\Link;
use App\Repositories\LinkRepository;
use App\Models\Tag;
use App\Repositories\TagRepository;
use App\Handlers\ItemHandler;

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
            $search = new SearchHandler($user);
            $cache = new CacheHandler($user);
            $items = new ItemRepository(new Item(), $user);
            $links = new LinkRepository(new Link(), $user);
            $notes = new NoteRepository(new Note(), $user);
            $tags  = new TagRepository(new Tag(), $user);
            return new ItemHandler($search, $cache, $items, $links, $notes, $tags, $user);
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
