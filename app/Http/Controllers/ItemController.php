<?php

namespace App\Http\Controllers;

use App\Interfaces\ItemHandlerInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ItemController
 * @package App\Http\Controllers
 */
class ItemController extends Controller
{

    /**
     * Store a newly created Item in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param ItemHandlerInterface $itemHandler
     * @return Response
     */
    public function store(Request $request, ItemHandlerInterface $itemHandler)
    {
        $item = $itemHandler->create($request->input());
        return view('item', ['item' => $item]);
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
        try {
            $itemHandler->destroy($id);
        }
        catch (\Exception $e) {
            return \Response::redirectTo('/')->with('error', $e->getMessage());
        }
        return \Response::redirectTo('/');
    }
}
