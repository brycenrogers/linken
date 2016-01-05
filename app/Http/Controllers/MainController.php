<?php

namespace App\Http\Controllers;

use App\Console\Commands\GenerateThumbnails;
use App\Handlers\DiscoverCacheHandler;
use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\ImageHandlerInterface;
use App\Interfaces\SearchHandlerInterface;
use App\Interfaces\UserCacheHandlerInterface;
use App\Interfaces\UserItemRepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class MainController
 * @package App\Http\Controllers
 */
class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get items for display
     *
     * @param Request $request
     * @param UserCacheHandlerInterface $cacheHandler
     * @param UserItemRepositoryInterface $itemRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAll(Request $request, UserCacheHandlerInterface $cacheHandler, UserItemRepositoryInterface $itemRepo)
    {
        if ((!$request->has('page') || ($request->has('page') && $request->input('page') == 1))) {
            // Requesting the main page, load from cache if available
            if ($cacheHandler->has(UserCacheHandlerInterface::MAINPAGE)) {
                $items = $cacheHandler->get(UserCacheHandlerInterface::MAINPAGE);
            } else {
                // Cache not available, get all items for user
                $items = $itemRepo->getItemsPaginated(20);

                // Save in cache for next request
                $cacheHandler->set(UserCacheHandlerInterface::MAINPAGE, $items);
            }
        } else {
            // Page other than front page was requested, pull from db
            $items = $itemRepo->getItemsPaginated(20);
        }

        $title = "List";
        return view('all', ['items' => $items, 'title' => $title]);
    }

//    public function test(
//        ImageHandlerInterface $imageHandler,
//        CacheHandlerInterface $cacheHandler)
//    {
//        $generate = new GenerateThumbnails();
//        $generate->handle($imageHandler, $cacheHandler);
//    }
}
