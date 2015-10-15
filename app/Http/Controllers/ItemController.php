<?php

namespace App\Http\Controllers;

use App\Item;
use App\Link;
use App\Note;
use App\Tag;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Cache;
use SearchIndex;

class ItemController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $type = $request->input('type');
        $value = $request->input('value');
        $tags = $request->input('tags');
        $tags = explode("|", $tags);

        $item = new Item();
        $item->value = $value;
        $item->description = $request->input('description');
        $item->user()->associate($user);
        $item->save();

        if ($type == 'Link') {
            $link = new Link();
            $link->url = urldecode($request->input('url'));
            $link->photo = urldecode($request->input('photo_url'));
            $link->title = $request->input('title');
            $link->save();
            $link->items()->save($item);
        } else {
            $note = new Note();
            $note->save();
            $note->items()->save($item);
        }

        // Save tags
        foreach ($tags as $tag) {
            if ($tag == "") {
                continue;
            }
            $newTag = new Tag();
            $newTag = $newTag->firstOrNew(['name' => $tag, 'user_id' => $user->id]);
            if ( ! $newTag->id ) {
                $newTag->user()->associate($user);
                $newTag->save();
            }
            $item->tags()->attach($newTag->id);
        }

        // Forget the 'all' and 'tags' caches so they can be regenerated
        $cacheKey = 'getAll' . $user->id;
        Cache::store('memcached')->forget($cacheKey);

        // Prep data for elasticsearch
        $additionalData = ['user_id' => $user->id, 'tags' => trim(implode(' ', $tags))];

        if (isset($link)) {
            $additionalData['title'] = $link->title;
            $additionalData['url'] = $link->url;
        }

        if (isset($link)) {
            SearchIndex::upsertToIndex($link);
        } elseif (isset($note)) {
            SearchIndex::upsertToIndex($note);
        }

        return \Response::json('Success');
    }

    /**
     * Find items that relate to the tags passed in
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function findItemsForTags(Request $request)
    {
        $tags = $request->input('q');
        $tags = explode(",", $tags);

        $items = Item::whereHas('tags', function ($query) use ($tags) {
            $query->whereIn('name', $tags);
        })->orderBy('created_at', 'desc')->get();

        $title = "Tags";
        return view('all', ['items' => $items, 'title' => $title]);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }
}
