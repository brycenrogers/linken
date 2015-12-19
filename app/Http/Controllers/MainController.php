<?php

namespace App\Http\Controllers;

use App\Interfaces\CacheHandlerInterface;
use App\Models\Item;
use Auth;
use Cache;
use Illuminate\Http\Request;

class MainController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAll(Request $request, CacheHandlerInterface $cacheHandler)
    {
        $user = Auth::user();

        // If the 'page' variable is not set (home page) or it is set to 1, load from Memcache if available
        if ((!$request->has('page') || ($request->has('page') && $request->input('page') == 1))) {

            if ($cacheHandler->has(CacheHandlerInterface::MAINPAGE, $user->id)) {
                $items = $cacheHandler->get(CacheHandlerInterface::MAINPAGE, $user->id);
            }

            $cacheKey = 'getAll' . $user->id;
            // Get the list from the cache, or regenerate it
            if (Cache::store('memcached')->has($cacheKey)) {
                $items = Cache::store('memcached')->get($cacheKey);
            } else {
                // Get all items for user
                $items = Item::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->simplePaginate(20);
                // Save in cache
                Cache::store('memcached')->put($cacheKey, $items, 2880);
            }
        } else {
            // Page other than front page was requested, pull from db
            $items = Item::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->simplePaginate(20);
        }

        $title = "All";

        return view('all', ['items' => $items, 'title' => $title]);
    }
}
