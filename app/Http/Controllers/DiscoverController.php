<?php

namespace App\Http\Controllers;

use App\Handlers\DiscoverCacheHandler;
use App\Interfaces\TagHandlerInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\CacheHandlerInterface;
use App\Http\Requests;

class DiscoverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Find links for the current user based on their recent tags, excluding any they added themselves
     *
     * @param DiscoverCacheHandler $discoverHandler
     * @param TagRepositoryInterface $userTagRepo
     * @param TagHandlerInterface $tagHandler
     * @param CacheHandlerInterface $cacheHandler
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function discover
    (
        DiscoverCacheHandler $discoverHandler,
        TagRepositoryInterface $userTagRepo,
        TagHandlerInterface $tagHandler,
        CacheHandlerInterface $cacheHandler
    ) {
        // Get specified tag from GET
        $tags = request()->get('tags');
        $tagsArray = [];

        if ($tags) {
            // Convert tags string into array and clean the tag names
            $tagsArray = explode(' ', $tags);
            foreach ($tagsArray as $key => $tag) {
                $tagsArray[$key] = str_replace('_', ' ', $tag);
            }

            // Get discovered items for tag
            $items = $discoverHandler->getLinksForUser($userTagRepo, $tagHandler, $cacheHandler, $tagsArray);
        } else {
            // Get all discovered items
            $items = $discoverHandler->getLinksForUser($userTagRepo, $tagHandler, $cacheHandler);
        }

        $title = "Discover";
        return view('discover', ['items' => $items, 'title' => $title, 'tags' => $tagsArray]);
    }
}