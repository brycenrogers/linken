<?php

namespace App\Http\Controllers;

use App\Console\Commands\GenerateThumbnails;
use App\Handlers\DiscoverCacheHandler;
use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\ImageHandlerInterface;
use App\Interfaces\SearchHandlerInterface;
use App\Interfaces\ItemRepositoryInterface;
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
     * @param CacheHandlerInterface $cacheHandler
     * @param ItemRepositoryInterface $itemRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAll(Request $request, CacheHandlerInterface $cacheHandler, ItemRepositoryInterface $itemRepo)
    {
        if ((!$request->has('page') || ($request->has('page') && $request->input('page') == 1))) {
            // Requesting the main page, load from cache if available
            if ($cacheHandler->has(CacheHandlerInterface::MAINPAGE)) {
                $items = $cacheHandler->get(CacheHandlerInterface::MAINPAGE);
            } else {
                // Cache not available, get all items for user
                $items = $itemRepo->getItemsPaginated(20);

                // Save in cache for next request
                $cacheHandler->set(CacheHandlerInterface::MAINPAGE, $items);
            }
        } else {
            // Page other than front page was requested, pull from db
            $items = $itemRepo->getItemsPaginated(20);
        }

        $title = "List";
        return view('all', ['items' => $items, 'title' => $title]);
    }

    public function test(
        ImageHandlerInterface $imageHandler,
        CacheHandlerInterface $cacheHandler,
        SearchHandlerInterface $searchHandler)
    {
        $generate = new GenerateThumbnails();
        $generate->handle($imageHandler, $cacheHandler, $searchHandler);
    }
}
