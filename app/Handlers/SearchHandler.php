<?php

namespace App\Handlers;

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

    public function search($term, $sortColumn = null, $sortDirection = 'desc', $limit = 50)
    {
        return $this->basicSearch($term, $sortColumn, $sortDirection, $limit);
    }

    public function basicSearch($term, $sortColumn = null, $sortDirection = 'desc', $limit = 50)
    {
        $userId = null;
        if ($this->user) {
            $userId = $this->user->id;
        }

        return $this->performSearch($this->basicQuery($term, $sortColumn, $sortDirection, $limit, $userId));
    }

    public function filteredSearch($type, $term, $sortColumn = null, $sortDirection = 'desc', $limit = 50)
    {
        $userId = null;
        if ($this->user) {
            $userId = $this->user->id;
        }

        return $this->performSearch($this->filteredQuery($type, $term, $sortColumn, $sortDirection, $limit, $userId));
    }

    /**
     * Perform a search for the query term
     *
     * @param $term string
     * @param null $sortColumn
     * @param string $sortDirection
     * @param int $limit
     * @return array
     */
    public function performSearch($queryType)
    {
        $hits = SearchIndex::getResults($queryType);

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
            $item->user_id = $itemArray['user_id'];
            $item->user = new User();
            $item->user->id = $itemArray['user_id'];
            $item->user->user_photo = $itemArray['user_photo'];
            $items[] = $item;
        }

        return $items;
    }

    /**
     * Perform a basic search on the index
     *
     * @param $term
     * @param $userId
     * @param $sortCol
     * @param $sortDirection
     * @return array
     */
    public function filteredQuery($type, $term, $sortCol, $sortDirection, $limit, $userId)
    {
        // Add user constraint if specified
        if ($userId) {
            $query = [
                'body' => [
                    'from' => 0,
                    'size' => $limit,
                    'query' => [
                        'bool' => [
                            'must' => [
                                [
                                    'term' => [
                                        'user_id' => $userId
                                    ]
                                ],
                                [
                                    'term' => [
                                        $type => $term
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        } else {
            $query = [
                'body' => [
                    'from' => 0,
                    'size' => $limit,
                    'query' => [
                        'bool' => [
                            'must' => [
                                [
                                    'term' => [
                                        $type => $term
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }

        if ($sortCol) {
            $query['body']['sort'] = ['created_at' => ['order' => $sortDirection]];
        }

        return $query;
    }

    /**
     * Perform a basic search on the index
     *
     * @param $term
     * @param $userId
     * @param $sortCol
     * @param $sortDirection
     * @return array
     */
    public function basicQuery($term, $sortCol, $sortDirection, $limit, $userId)
    {
        // Add user constraint if specified
        if ($userId) {
            $query = [
                'body' => [
                    'from' => 0,
                    'size' => $limit,
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
                                ],
                                [
                                    'match' => [
                                        'user_id' => $userId
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        } else {
            $query = [
                'body' => [
                    'from' => 0,
                    'size' => $limit,
                    'query' => [
                        'fuzzy_like_this' => [
                            '_all' => [
                                'like_text' => $term,
                                'fuzziness' => 0.5
                            ]
                        ]
                    ]
                ]
            ];
        }

        if ($sortCol) {
            $query['body']['sort'] = ['date' => ['order' => $sortDirection]];
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
        }

        // Get all items and reindex them
        Item::chunk(100, function($items) {
            foreach ($items as $item) {
                SearchIndex::upsertToIndex($item->itemable);
            }
        });

        return true;
    }

}