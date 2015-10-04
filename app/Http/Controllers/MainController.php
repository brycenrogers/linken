<?php

namespace App\Http\Controllers;

use App\Item;
use Auth;
use Cache;

class MainController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAll()
    {
        $user = Auth::user();
        $cacheKey = 'getAll' . $user->id;

        // Get the list from the cache, or regenerate it
        if (Cache::store('memcached')->has($cacheKey)) {
            $items = Cache::store('memcached')->get($cacheKey);
        } else {
            // Get all items for user
            $items = Item::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->take(30)->get();
            // Save in cache
            Cache::store('memcached')->put($cacheKey, $items, 2880);
        }

        return view('all', ['items' => $items]);
    }
}
