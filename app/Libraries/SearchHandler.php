<?php

namespace App\Libraries;

use App\Interfaces\SearchHandlerInterface;
use App\Interfaces\UserSearchHandlerInterface;
use App\Models\Item;
use App\Models\Link;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use SearchIndex;

/**
 * Class SearchHandler
 *
 * Responsible for performing search requests on ElasticSearch
 *
 * @package App\Libraries
 * @provider App\Providers\SearchHandlerServiceProvider
 */
class SearchHandler implements SearchHandlerInterface, UserSearchHandlerInterface {

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

        // Loop through the search hits and return Eloquent models for them
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

    /**
     * Perform a basic search on the index
     *
     * @param $term
     * @param $userId
     * @return array
     */
    public function basicQuery($term, $userId)
    {
        $query = [
            'body' => [
                'from' => 0,
                'size' => 500,
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'fuzzy_like_this' => [
                                    '_all' => [
                                        'like_text' => $term,
                                        'fuzziness' => 0.5
                                    ]
                                ]
                            ]
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

    /**
     * Rebuild the search index
     * @return bool
     * @throws \Exception
     */
    public function reindex()
    {
        // Clear the search index
        try {
            SearchIndex::clearIndex();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        // Get all items and reindex them
        $items = Item::all();
        foreach ($items as $item) {
            SearchIndex::upsertToIndex($item->itemable);
        }

        return true;
    }

}