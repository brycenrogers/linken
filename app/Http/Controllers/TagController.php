<?php

namespace App\Http\Controllers;

use App\Interfaces\UserTagRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests;
use Cache;
use Auth;
use App\Models\Tag;
use App\Models\Item;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(Request $request, UserTagRepositoryInterface $tagRepo)
    {
        $query = $request->input('q');

        $tagObjs = $tagRepo->search($query);

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
                $display .= "<div class='letter-headers'>$letter</div>";
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

    public function discover()
    {
        // Get top 10 recent tags
        $tags = Tag::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->lists('name');

        // Find top 2 links for each tag, regardless of user
        $items = Item::whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('name', $tags);
            })
            ->where('itemable_type', 'App\Models\Link')
            ->where('user_id', '<>', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->simplePaginate();

        $title = "Discover";
        return view('all', ['items' => $items, 'title' => $title]);
    }
}
