<?php

namespace App\Http\Controllers;

use App\Interfaces\TagHandlerInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\SearchHandlerInterface;
use Auth;
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
     *\
     * @param TagHandlerInterface $tagHandler
     * @param CacheHandlerInterface $cacheHandler
     * @param TagRepositoryInterface $tagRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function search
    (
        TagHandlerInterface $tagHandler,
        CacheHandlerInterface $cacheHandler,
        TagRepositoryInterface $tagRepo
    ) {
        // Get the query term
        $query = request()->input('q');
        $scope = request()->input('scope');
        $hits = [];

        // Get the tags from cache and search them
        switch ($scope) {
            case 'user':
                // Search User's Tags
                $input = preg_quote($query, '~');
                $tags = $tagHandler->getTagsForUser($cacheHandler, $tagRepo);
                $results = preg_grep('~^' . $input . '~', $tags);

                // Format the hits for select2
                foreach($results as $tag) {
                    $hits['id'] = $tag;
                    $hits['text'] = $tag;
                }
                break;

            case 'all':
                // Search all Tags in DB
                $results = $tagRepo->search($query);

                // Format the hits for select2
                foreach($results as $tag) {
                    $hits['id'] = $tag->id;
                    $hits['text'] = $tag->name;
                }
                break;

            default:
                $hits = [];
                break;
        }

        return response()->json(['items' => [$hits]]);
    }

    public function getTagsPane
    (
        TagHandlerInterface $tagHandler,
        CacheHandlerInterface $cacheHandler,
        TagRepositoryInterface $tagRepo
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
     * @param SearchHandlerInterface $searchHandler
     * @return \Illuminate\View\View
     */
    public function findItemsForTags(Request $request, SearchHandlerInterface $searchHandler)
    {
        $q = $request->input('q');
        $items = $searchHandler->filteredSearch('tags', $q, Auth::user(), 'created_at', 'desc');
        $title = "Tag : " . $q;
        return view('all', ['items' => $items, 'title' => $title]);
    }
}
