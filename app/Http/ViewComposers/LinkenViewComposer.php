<?php

namespace App\Http\ViewComposers;

use Auth;
use Cache;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Route;

class LinkenViewComposer
{
    /**
     * The user object
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new control panel composer.
     *
     * @param  \App\Models\User $user
     */
    public function __construct(\App\Models\User $user)
    {
        $this->user = Auth::user();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $cacheKey = 'tags' . $this->user->id;

        // Get the list from the cache, or regenerate it
        if (Cache::has($cacheKey)) {
            $tags = Cache::get($cacheKey);
        } else {
            // Get all items for user
            $tags = Tag::where('user_id', $this->user->id)->orderBy('created_at', 'desc')->get();

            // Save in cache
            Cache::put($cacheKey, $tags, 5);
        }

        $request = Route::getCurrentRequest();

        $view->with('user', $this->user)->with('tags', $tags)->with('requestPath', $request->path());
    }
}