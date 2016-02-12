<?php

namespace App\Http\Controllers;

use App\Console\Commands\GenerateThumbnails;
use App\Handlers\DiscoverCacheHandler;
use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\ImageHandlerInterface;
use App\Interfaces\SearchHandlerInterface;
use App\Interfaces\ItemRepositoryInterface;
use Auth;
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
        $pageRequested = $request->input('page');
        $isMainPage = (is_null($pageRequested) or $pageRequested === 1);

        if ($isMainPage) {
            // Load from cache if available
            $items = $cacheHandler->get(CacheHandlerInterface::MAINPAGE);
        } else {
            $items = $itemRepo->getItemsPaginated(20, Auth::user());
        }

        // Main page data not available in cache
        if ( ! $items) {

            // Get paginated items for user
            $items = $itemRepo->getItemsPaginated(20, Auth::user());

            // Save in cache for next request
            $cacheHandler->set(CacheHandlerInterface::MAINPAGE, $items);
        }

        $title = "List";
        return view('all', ['items' => $items, 'title' => $title]);
    }

    public function test(
        DiscoverCacheHandler $discoverCacheHandler,
        ImageHandlerInterface $imageHandler,
        CacheHandlerInterface $cacheHandler,
        SearchHandlerInterface $searchHandler)
    {
        $generate = new GenerateThumbnails();
        $generate->handle($imageHandler, $cacheHandler, $searchHandler);

        $discoverCacheHandler->generateAll($searchHandler, $cacheHandler);
    }
}
