<?php

namespace App\Http\Controllers;

use App\Interfaces\SearchHandlerInterface;
use App\Interfaces\TagHandlerInterface;
use App\Interfaces\UserCacheHandlerInterface;
use App\Interfaces\UserItemRepositoryInterface;
use App\Interfaces\UserSearchHandlerInterface;
use App\Interfaces\UserTagRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Search for Tags, used by Select2
     *
     * @param Request $request
     * @param TagHandlerInterface $tagHandler
     * @param UserCacheHandlerInterface $cacheHandler
     * @param UserTagRepositoryInterface $tagRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function search
    (
        Request $request,
        TagHandlerInterface $tagHandler,
        UserCacheHandlerInterface $cacheHandler,
        UserTagRepositoryInterface $tagRepo
    ) {
        // Get the query term
        $query = $request->input('q');

        // Get the tags from cache and search them
        $input = preg_quote($query, '~');
        $tags = $tagHandler->getTagsForUser($cacheHandler, $tagRepo);
        $results = preg_grep('~^' . $input . '~', $tags);

        // Format the hits for select2
        $hits = [];
        foreach($results as $tag) {
            $hits['id'] = $tag;
            $hits['text'] = $tag;
        }

        return response()->json(['items' => [$hits]]);
    }

    public function getTagsPane
    (
        TagHandlerInterface $tagHandler,
        UserCacheHandlerInterface $cacheHandler,
        UserTagRepositoryInterface $tagRepo
    ) {
        $tags = $tagHandler->getTagsForUser($cacheHandler, $tagRepo);
        sort($tags, SORT_STRING);

        // Create an array to denote the starting letter of the tags
        $tagsDisplayArray = [];
        foreach ($tags as $tag) {

            // Get first letter of tag and add to the array if it doesn't exist
            $letter = strtoupper(substr($tag, 0, 1));
            if(!array_key_exists($letter, $tagsDisplayArray)) {
                $tagsDisplayArray[$letter] = [];
            }

            // Add tag to array for letter
            $tagsDisplayArray[$letter][] = $tag;
        }

        return \Response::view('panes.tagsPane', ['tags' => $tagsDisplayArray]);
    }

    /**
     * Find items that relate to the tags passed in
     *
     * @param Request $request
     * @param UserSearchHandlerInterface $searchHandler
     * @return \Illuminate\View\View
     */
    public function findItemsForTags(Request $request, UserSearchHandlerInterface $searchHandler)
    {
        $q = $request->input('q');
        $items = $searchHandler->filteredSearch('tags', $q, 'created_at', 'desc');
        $title = "Tag : " . $q;
        return view('all', ['items' => $items, 'title' => $title]);
    }
}
