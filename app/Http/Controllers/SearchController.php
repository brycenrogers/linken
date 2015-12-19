<?php

namespace App\Http\Controllers;

use Auth,
    Response,
    Illuminate\Http\Request,
    SearchIndex,
    App\Models\Item;

class SearchController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(Request $request)
    {
        $user = Auth::user();

        // If the 'page' variable is not set (home page) or it is set to 1, load from Memcache if available
        if ($request->has('q')) {

            $query = $request->input('q');
            $hits = SearchIndex::getResults($this->fuzzyQuery($request->input('q'), $user->id));

            $items = [];
            foreach($hits['hits']['hits'] as $hit) {
                $itemArray = $hit['_source'];
                $item = new \App\Models\Item();
                $item->id = intval($hit['_id']);
                if (array_key_exists('url', $itemArray)) {
                    $item->itemable = new \App\Models\Link();
                    $item->itemable->url = $itemArray['url'];
                    $item->itemable->photo = $itemArray['photo'];
                } else {
                    $item->itemable = new \App\Models\Note();
                }
                $item->value = $itemArray['value'];
                $item->description = $itemArray['description'];
                $tagsArray = json_decode($itemArray['tags'], true);
                $item->tags = [];
                if ($tagsArray) {
                    $tagsObjArray = [];
                    foreach ($tagsArray as $tag) {
                        $tagObj = new \App\Models\Tag();
                        $tagObj->name = $tag;
                        $tagsObjArray[] = $tagObj;
                    }
                    $item->tags = $tagsObjArray;
                }
                $items[] = $item;
            }

            $title = "Search";

            return view('all', ['items' => $items, 'title' => $title, 'q' => $query]);
        } else {
            // Page other than front page was requested, pull from db
            return Response::redirectTo('/');
        }
    }

    public function fuzzyQuery($term, $userId)
    {
        return [
            'body' =>
                [
                    'from' => 0,
                    'size' => 500,
                    'query' =>
                        [
                            'fuzzy_like_this' =>
                                [
                                    '_all' =>
                                        [
                                            'like_text' => $term,
                                            'fuzziness' => 0.5,
                                        ],
                                ],
                        ],
                ]
        ];
    }

    public function reindex() {
        // Clear the search index
        try {
            SearchIndex::clearIndex();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        // Get all items and reindex them
        $items = Item::all();
        foreach ($items as $item) {
            SearchIndex::upsertToIndex($item->itemable);
        }

        return \Response::json('Reindex Successful');
    }
}