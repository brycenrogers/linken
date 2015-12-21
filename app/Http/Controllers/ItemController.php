<?php

namespace App\Http\Controllers;

use App\Interfaces\ItemHandlerInterface;
use App\Interfaces\UserItemRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ItemController
 * @package App\Http\Controllers
 */
class ItemController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param ItemHandlerInterface $itemHandler
     * @return Response
     */
    public function store(Request $request, ItemHandlerInterface $itemHandler)
    {
        // Create the new item
        $item = $itemHandler->create($request->input());

        return view('item', ['item' => $item]);
    }


    /**
     * Find items that relate to the tags passed in
     *
     * @param Request $request
     * @param UserItemRepositoryInterface $itemRepo
     * @return \Illuminate\View\View
     */
    public function findItemsForTags(Request $request, UserItemRepositoryInterface $itemRepo)
    {
        $q = $request->input('q');
        $tags = explode(",", $q);

        $items = $itemRepo->itemsForTags($tags);

        $title = "Tags";
        $subControl = "<a href='/' class='btn btn-default'><span class='glyphicon glyphicon-menu-left'></span>&nbsp;Back</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tag: " . $q;
        return view('all', ['items' => $items, 'title' => $title, 'subControl' => $subControl]);
    }

    /**
     * Delete an item
     *
     * @param  int $id
     * @param ItemHandlerInterface $itemHandler
     * @return Response
     */
    public function destroy($id, ItemHandlerInterface $itemHandler)
    {
        // Try to delete the item and redirect with any errors
        try {
            $itemHandler->destroy($id);
        }
        catch (\Exception $e) {
            return \Response::redirectTo('/')->with('error', $e->getMessage());
        }

        return \Response::redirectTo('/');
    }
}
