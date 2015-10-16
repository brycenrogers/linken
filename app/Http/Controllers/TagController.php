<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache;
use Auth;
use App\Tag;

class TagController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->input('q');
        $tagObjs = Tag::where('name', 'like', $q . "%")->where('user_id', '=', Auth::user()->id)->get();

        $tags = [];
        foreach($tagObjs as $tag) {
            $tags[] = ['tag_id' => $tag->id, 'tag_value' => $tag->name];
        }

        return \Response::json($tags);
    }

    public function getTagsPane()
    {
        $user = Auth::user();
        $cacheKey = 'getTagsPane' . $user->id;
        // Get the list from the cache, or regenerate it
        if (Cache::store('memcached')->has($cacheKey)) {
            $display = Cache::store('memcached')->get($cacheKey);
        } else {
            $tags = Tag::where('user_id', '=', Auth::user()->id)->orderBy('name', 'asc')->get();
            $tagsDisplayArray = [];
            foreach ($tags as $tag) {

                // Get first letter of tag and add to the array if it doesn't exist
                $letter = strtoupper(substr($tag->name, 0, 1));
                if(!array_key_exists($letter, $tagsDisplayArray)) {
                    $tagsDisplayArray[$letter] = [];
                }

                // Add tag to array for letter
                $tagsDisplayArray[$letter][] = $tag->name;
            }
            // Build display
            $display = "<ul>";
            foreach ($tagsDisplayArray as $letter => $tagNameArray) {
                $display .= "<div class='letter-headersssss'>$letter</div>";
                $display .= "<li class='divider' role='separator'></li>";
                foreach ($tagNameArray as $tag) {
                    $display .= "<li><div class='tag'>$tag</div></li>";
                }
            }
            $display .= "</ul>";
            // Save in cache
            Cache::store('memcached')->put($cacheKey, $display, 2880);
        }
        return \Response::view('panes.tagsPane', ['display' => $display]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

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
        //
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
