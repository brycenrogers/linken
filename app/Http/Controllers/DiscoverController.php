<?php

namespace App\Http\Controllers;

use App\Handlers\DiscoverCacheHandler;
use App\Interfaces\TagHandlerInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\UserCacheHandlerInterface;
use App\Interfaces\UserTagRepositoryInterface;
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
     * @param UserTagRepositoryInterface $userTagRepo
     * @param TagHandlerInterface $tagHandler
     * @param UserCacheHandlerInterface $cacheHandler
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function discover
    (
        DiscoverCacheHandler $discoverHandler,
        UserTagRepositoryInterface $userTagRepo,
        TagHandlerInterface $tagHandler,
        UserCacheHandlerInterface $cacheHandler
    ) {
        // Get specified tag from GET
        $tag = request()->get('tags');

        if ($tag) {
            // Get discovered items for tag
            $items = $discoverHandler->getLinksForUser($userTagRepo, $tagHandler, $cacheHandler, $tag);
        } else {
            // Get all discovered items
            $items = $discoverHandler->getLinksForUser($userTagRepo, $tagHandler, $cacheHandler);
        }

        $title = "Discover";
        return view('discover', ['items' => $items, 'title' => $title]);
    }
}