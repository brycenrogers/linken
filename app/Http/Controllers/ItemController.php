<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddItemPostRequest;
use App\Http\Requests\ShareEmailPostRequest;
use App\Http\Requests\UpdateItemPostRequest;
use App\Interfaces\ItemHandlerInterface;
use Auth;
use Response;

/**
 * Class ItemController
 * @package App\Http\Controllers
 */
class ItemController extends Controller
{

    /**
     * Add a newly created Item to storage.
     *
     * @param AddItemPostRequest $request
     * @param ItemHandlerInterface $itemHandler
     * @return Response
     */
    public function add(AddItemPostRequest $request, ItemHandlerInterface $itemHandler)
    {
        $item = $itemHandler->create($request->input(), Auth::user());
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
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], 422);
        }
        return response()->json(['type' => 'success', 'message' => 'Deleted!']);
    }

    /**
     * Share item with email addresses
     *
     * @param ShareEmailPostRequest $request
     * @param ItemHandlerInterface $itemHandler
     * @return \Illuminate\Http\JsonResponse
     */
    public function email(ShareEmailPostRequest $request, ItemHandlerInterface $itemHandler)
    {
        try {
            $itemHandler->email($request->input());
        }
        catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], 422);
        }
        return response()->json(['type' => 'success', 'message' => 'Shares sent!']);
    }

    /**
     * Update an item
     *
     * @param UpdateItemPostRequest $request
     * @param ItemHandlerInterface $itemHandler
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateItemPostRequest $request, ItemHandlerInterface $itemHandler)
    {
        $inputs = $request->input();
        try {
            $item = $itemHandler->update($inputs);
            return view('item', ['item' => $item]);
        }
        catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], 422);
        }
    }
}
