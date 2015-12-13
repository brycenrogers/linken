<?php

namespace App\Http\Controllers;

use App\Interfaces\ImageHandlerInterface;
use App\Item;
use App\Link;
use App\Note;
use App\Tag;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Cache;
use Illuminate\Http\Response;
use SearchIndex;

class ItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param ImageHandlerInterface $imageHandler
     * @return Response
     */
    public function store(Request $request, ImageHandlerInterface $imageHandler)
    {
        $user = Auth::user();

        // Gather post data
        $type = $request->input('type');
        $value = $request->input('value');
        $tags = $request->input('tags');
        $tags = explode("|", $tags);

        // Build new Item for User
        $item = new Item();
        $item->value = $value;
        $item->description = $request->input('description');
        $item->user()->associate($user);
        $item->save();

        if ($type == 'Link') {
            $link = new Link();
            $link->url = urldecode($request->input('url'));
            $photoUrl = urldecode($request->input('photo_url'));
            if ($photoUrl) {
                // Try to generate a thumbnail for the desired photo
                try {
                    $link->photo = $imageHandler->generateThumbnail($photoUrl);
                }
                catch (\Exception $e) {
                    error_log($e);
                }
            }
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

        if (isset($link)) {
            SearchIndex::upsertToIndex($link);
        } elseif (isset($note)) {
            SearchIndex::upsertToIndex($note);
        }

        //return \Response::json('Success');
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
        })->orderBy('created_at', 'desc')->simplePaginate();

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
