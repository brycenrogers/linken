<?php

namespace App\Repositories;

use App\Interfaces\CacheHandlerInterface;
use App\Models\Item;

class ItemRepository extends BaseRepository {

    /**
     * The Item instance
     *
     * @var \App\Models\Item
     */
    protected $item;

    protected $cacheHandler;

    /**
     * Create a new ItemRepository instance.
     *
     * @param  \App\Models\Item $item
     */
    public function __construct(Item $item, CacheHandlerInterface $cacheHandler)
    {
        $this->model = $item;
        $this->cacheHandler = $cacheHandler;
    }

    /**
     * Save or update an Item
     *
     * @param $item \App\Models\Item
     * @param $inputs
     * @param $userId int
     * @return mixed
     */
    private function saveItem($item, $inputs, $userId)
    {
        $item->value = $inputs['value'];
        $item->content = $inputs['description'];
        if ($userId) {
            $item->user_id = $userId;
        }
        $item->save();

        return $item;
    }

    /**
     * Create an Item and it's derived class
     *
     * @param $inputs
     * @param $userId
     */
    public function store($inputs, $userId)
    {
        $item = $this->saveItem(new $this->model, $inputs, $userId);

        // Save derived type
        switch($inputs['type']) {
            case 'Link':
                $link = new \App\Models\Link();
                $link->url = urldecode($inputs['url']);
                $link->photo_url = urldecode($inputs['photo_url']);
                $link->title = $inputs['title'];
                $link->save();
                $link->items()->save($item);
                \SearchIndex::upsertToIndex($link);
                break;
            case 'Note':
                $note = new \App\Models\Note();
                $note->save();
                $note->items()->save($item);
                \SearchIndex::upsertToIndex($note);
                break;
        }

        // Save tags
        $tags = $inputs['tags'];
        $tags = explode("|", $tags);
        foreach ($tags as $tag) {
            if ($tag == "") {
                continue;
            }
            $newTag = new \App\Models\Tag();
            $newTag = $newTag->firstOrNew(['name' => $tag, 'user_id' => $userId]);
            if ( ! $newTag->id ) {
                $newTag->user_id = $userId;
                $newTag->save();
            }
            $item->tags()->attach($newTag->id);
        }

        // Reset cache
        $this->cacheHandler->del(CacheHandlerInterface::MAINPAGE, $userId);
    }

    /**
     * Get a paginated result set of Items for a specific User
     *
     * @param $amount int
     * @param $userId int
     * @return mixed
     */
    public function getItemsPaginated($amount, $userId)
    {
        return Item::where('user_id', '=', $userId)
            ->orderBy('created_at', 'desc')
            ->simplePaginate($amount);
    }
}