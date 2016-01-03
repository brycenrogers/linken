<?php

namespace App\Http\Controllers;

use App\Handlers\DiscoverCacheHandler;
use App\Interfaces\TagHandlerInterface;
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
     * @param UserTagRepositoryInterface $tagRepo
     * @param TagHandlerInterface $tagHandler
     * @param UserCacheHandlerInterface $cacheHandler
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function discover
    (
        DiscoverCacheHandler $discoverHandler,
        UserTagRepositoryInterface $tagRepo,
        TagHandlerInterface $tagHandler,
        UserCacheHandlerInterface $cacheHandler
    ) {
        // Get discovered items
        $items = $discoverHandler->getLinksForUser($tagRepo, $tagHandler, $cacheHandler);

        $title = "Discover";
        return view('discover', ['items' => $items, 'title' => $title]);
    }
}