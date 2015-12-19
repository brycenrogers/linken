<?php

namespace App\Http\Controllers;

use App\Interfaces\ItemHandlerInterface;
use App\Models\Item;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Cache;
use Illuminate\Http\Response;

/**
 * Class ItemController
 * @package App\Http\Controllers
 */
class ItemController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request, ItemHandlerInterface $itemHandler)
    {
        // Create the new item
        $item = $itemHandler->create($request->input());

        return view('item', ['item' => $item]);
    }



    /**
     * Find items that relate to the tags passed in
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function findItemsForTags(Request $request)
    {
        $q = $request->input('q');
        $tags = explode(",", $q);

        $items = Item::whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('name', $tags);
            })
            ->where('user_id', Auth::user()->id)
            ->where('itemable_type', 'App\Models\Link')
            ->orderBy('created_at', 'desc')
            ->simplePaginate();

        $title = "Tags";
        $subControl = "<a href='/' class='btn btn-default'><span class='glyphicon glyphicon-menu-left'></span>&nbsp;Back</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tag: " . $q;
        return view('all', ['items' => $items, 'title' => $title, 'subControl' => $subControl]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* @var $item \App\Item */
        $item = Item::find($id);

        // Make sure they own it
        $user = $item->user;
        $currentUser = Auth::user();
        if ($user != $currentUser) {
            return \Response::redirectTo('/');
        }

        // Delete
        $item->delete();

        // Delete from cache too
        $cacheKey = 'getAll' . $user->id;
        Cache::store('memcached')->forget($cacheKey);

        return \Response::redirectTo('/');
    }
}
