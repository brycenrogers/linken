<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use Illuminate\Http\Request;
use SearchIndex;
use App\Item;

class SearchController extends Controller
{

    public function search(Request $request)
    {
        $user = Auth::user();

        // If the 'page' variable is not set (home page) or it is set to 1, load from Memcache if available
        if ($request->has('q')) {

            $hits = SearchIndex::getResults($this->fuzzyQuery($request->input('q'), $user->id));

            $items = [];
            foreach($hits['hits']['hits'] as $hit) {
                $itemArray = $hit['_source'];
                if (array_key_exists('url', $itemArray)) {
                    $item = new \App\Link();
                    $item->url = $itemArray['url'];
                    //$item->photo = $hit['photo'];
                    $item->photo = "";
                } else {
                    $item = new \App\Note();
                }
                $item->value = $itemArray['title'];
                $item->description = $itemArray['description'];
                $item->tags = $itemArray['tags'];
                $items[] = $item;
            }

            $title = "Search";
            return view('all', ['items' => $items, 'title' => $title]);
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
        } catch (Exception $e) {
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