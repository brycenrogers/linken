<?php

namespace App\Http\Controllers;

use App\Item;
use App\Link;
use App\Note;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Cache;

class ItemController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $cacheKey = 'getAll' . $user->id;
        $type = $request->input('type');

        $item = new Item();
        $item->value = $request->input('value');
        $item->description = $request->input('description');
        $item->user()->associate($user);
        $item->save();

        if ($type == 'Link') {
            $link = new Link();
            $link->url = $request->input('value');
            $link->photo = $request->input('photo');
            $link->save();
            $link->items()->save($item);
        } else {
            $note = new Note();
            $note->save();
            $note->items()->save($item);
        }

        Cache::forget($cacheKey);

        return \Response::json('Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
