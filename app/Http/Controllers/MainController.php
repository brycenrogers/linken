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

        if (Cache::has($cacheKey)) {
            $items = Cache::get($cacheKey);
        } else {
            // Get all items for user
            $items = Item::with(['user' => function ($query) {
                $query->where('id', '=', Auth::user()->id);
            }, 'tags'])->orderBy('created_at', 'desc')->get();

            // Save in cache
            Cache::put($cacheKey, $items, 5);
        }

        return view('all', ['items' => $items]);
    }
}
