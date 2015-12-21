<?php

namespace App\Libraries;

use App\Models\Item;
use App\Models\Link;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use SearchIndex;

class SearchHandler {

    /**
     * Current User
     *
     * @var User
     */
    public $user;

    public function __construct(User $user = null)
    {
        if ($user) {
            $this->user = $user;
        }
    }

    /**
     * Perform a search for the query term
     *
     * @param $term string
     * @return array
     */
    public function search($term)
    {
        $hits = SearchIndex::getResults($this->basicQuery($term, $this->user->id));

        $items = [];
        foreach($hits['hits']['hits'] as $hit) {
            $itemArray = $hit['_source'];
            $item = new Item();
            $item->id = intval($hit['_id']);
            if (array_key_exists('url', $itemArray)) {
                $item->itemable = new Link();
                $item->itemable->url = $itemArray['url'];
                $item->itemable->photo = $itemArray['photo'];
            } else {
                $item->itemable = new Note();
            }
            $item->value = $itemArray['value'];
            $item->description = $itemArray['description'];
            $tagsArray = json_decode($itemArray['tags'], true);
            $item->tags = [];
            if ($tagsArray) {
                $tagsObjArray = [];
                foreach ($tagsArray as $tag) {
                    $tagObj = new Tag();
                    $tagObj->name = $tag;
                    $tagsObjArray[] = $tagObj;
                }
                $item->tags = $tagsObjArray;
            }
            $items[] = $item;
        }

        return $items;
    }

    public function basicQuery($term, $userId)
    {
        $query = [
            'body' => [
                'from' => 0,
                'size' => 500,
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => [
                                '_all' => $term
                            ]]
                        ]
                    ]
                ]
            ]
        ];

        // Add user constraint if specified
        if ($userId) {
            array_push($query['body']['query']['bool']['must'], ['match' => ['user_id' => $userId]]);
        }

        return $query;
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