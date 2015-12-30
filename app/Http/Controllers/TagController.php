<?php

namespace App\Http\Controllers;

use App\Interfaces\UserItemRepositoryInterface;
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
        // Get the query term
        $query = $request->input('q');

        // Find any tags that match the query term
        $tagObjs = $tagRepo->search($query);

        // Format the results for the UI
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

    /**
     * Find links for the current user based on their recent tags, excluding any they added themselves
     *
     * @param UserItemRepositoryInterface $itemRepo
     * @param UserTagRepositoryInterface $tagRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function discover(UserItemRepositoryInterface $itemRepo, UserTagRepositoryInterface $tagRepo)
    {
        // Get discovered items
        $items = $itemRepo->discovered($tagRepo);

        $title = "Discover";
        return view('all', ['items' => $items, 'title' => $title]);
    }
}
