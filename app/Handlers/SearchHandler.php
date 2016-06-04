<?php

namespace App\Handlers;

use App\Interfaces\Searchable;
use App\Interfaces\SearchHandlerInterface;
use App\Interfaces\UserSearchHandlerInterface;
use App\Models\Item;
use App\Models\Link;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use Auth;
use Elastica\Document;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Fuzzy;
use Elastica\Query\Term as TermQuery;
use Elastica\ResultSet;

/**
 * Class SearchHandler
 *
 * Responsible for performing search requests on ElasticSearch
 *
 * @package App\Libraries
 * @provider App\Providers\SearchHandlerServiceProvider
 */
class SearchHandler implements SearchHandlerInterface {

    /**
     * The Elastica Client instance
     */
    public $client;

    public function __construct()
    {
        $this->client = new \Elastica\Client();
    }

    /**
     * Perform the default search (currently fuzzy)
     *
     * @param $term
     * @param null $user
     * @param null $sortColumn
     * @param string $sortDirection
     * @param int $limit
     * @return array
     */
    public function search($term, $user = null, $sortColumn = null, $sortDirection = 'desc', $limit = 50)
    {
        return $this->basicSearch($term, $user, $sortColumn, $sortDirection, $limit);
    }

    /**
     * Perform a basic search (currently fuzzy)
     *
     * @param $term
     * @param null $user
     * @param null $sortColumn
     * @param string $sortDirection
     * @param int $limit
     * @return array
     */
    public function basicSearch($term, $user = null, $sortColumn = null, $sortDirection = 'desc', $limit = 50)
    {
        $query = new Query();
        $query->setFrom(0);
        $query->setSize($limit);

        $fuzzy = new Fuzzy();
        $fuzzy->setField('_all', $term);
        $fuzzy->setFieldOption('fuzziness', 2);

        if ($user) {
            $userId = $user->id;
            $boolQuery = new BoolQuery();
            $matchQuery = new Query\Match('user_id', $userId);
            $boolQuery->addMust($matchQuery);
            $boolQuery->addMust($fuzzy);
            $query->setQuery($boolQuery);
        }

        if ($sortColumn) {
            $query->addSort([$sortColumn => ['order' => $sortDirection]]);
        }

        $resultSet = $this->getTypedIndex()->search($query);

        return $this->getModels($resultSet);
    }

    /**
     * Perform a search based on the specified filter
     *
     * @param $typeTerms array
     * @param null $user
     * @param null $sortColumn
     * @param string $sortDirection
     * @param int $limit
     * @return array
     */
    public function filteredSearch($typeTerms, $user = null, $sortColumn = null, $sortDirection = 'desc', $limit = 50)
    {
        $query = new Query();
        $query->setFrom(0);
        $query->setSize($limit);

        $boolQuery = new BoolQuery();

        // Handle 'must' filters
        if (isset($typeTerms['must'])) {
            foreach($typeTerms['must'] as $type => $term) {
                $termQueryType = new TermQuery([$type => $term]);
                $boolQuery->addMust($termQueryType);
            }
        }

        // Handle 'should' filters
        if (isset($typeTerms['should'])) {
            foreach($typeTerms['should'] as $type => $term) {
                $termQueryType = new TermQuery([$type => $term]);
                $boolQuery->addShould($termQueryType);
            }
        }

        $query->setQuery($boolQuery);

        if ($user) {
            $userId = $user->id;
            $termQueryUser = new TermQuery(['user_id' => $userId]);
            $boolQuery->addMust($termQueryUser);
        }

        if ($sortColumn) {
            $query->addSort([$sortColumn => ['order' => $sortDirection]]);
        }

        $resultSet = $this->getTypedIndex()->search($query);

        return $this->getModels($resultSet);
    }

    /**
     * Get a list of the models for the result set
     *
     * @param ResultSet $resultSet
     * @return array
     */
    public function getModels(ResultSet $resultSet)
    {
        // Loop through the search hits and return Eloquent models for them
        $items = [];
        $hits = $resultSet->getResults();
        foreach($hits as $hit) {
            $itemArray = $hit->getSource();
            $item = new Item();
            $item->id = intval($hit->getId());
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
     * Get the Item index
     *
     * @return \Elastica\Index
     */
    private function getIndex()
    {
        return $this->client->getIndex('items');
    }

    /**
     * Get the Item index for Items
     *
     * @return \Elastica\Type
     */
    private function getTypedIndex()
    {
        return $this->getIndex()->getType('item');
    }

    /**
     * Add an item to the index
     *
     * @param Searchable $item
     * @return \Elastica\Response
     */
    public function add(Searchable $item)
    {
        return $this->getTypedIndex()->addDocument(new Document($item->getSearchableId(), $item->getSearchableBody()));
    }

    /**
     * Update an existing item in the index
     *
     * @param Searchable $item
     * @return \Elastica\Response
     */
    public function update(Searchable $item)
    {
        return $this->getTypedIndex()->updateDocument(new Document($item->getSearchableId(), $item->getSearchableBody()));
    }

    /**
     * Remove an item from the index
     *
     * @param $id String ID of Item to be removed
     * @return \Elastica\Response
     */
    public function remove($id)
    {
        return $this->getTypedIndex()->deleteById($id);
    }

    /**
     * Rebuild the search index
     * @return bool
     * @throws \Exception
     */
    public function reindex()
    {
        // Clear the search index
        if ($this->getIndex()->exists()) {
            $this->getIndex()->delete();
            $this->getIndex()->create();
        }

        // Get all items and reindex them
        Item::with('itemable')->chunk(100, function($items) {
            $documents = [];
            foreach($items as $item) {
                /* @var $itemable Searchable */
                $itemable = $item->itemable;
                $documents[] = new Document($itemable->getSearchableId(), $itemable->getSearchableBody());
            }
            $this->getTypedIndex()->addDocuments($documents);
        });

        return true;
    }

}