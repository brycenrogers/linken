<?php

namespace App\Repositories;

use App\Interfaces\ItemRepositoryInterface;
use App\Models\Item;
use App\Models\User;
use App\Interfaces\UserItemRepositoryInterface;

/**
 * Class ItemRepository
 * @package App\Repositories
 */
class ItemRepository extends BaseRepository implements ItemRepositoryInterface, UserItemRepositoryInterface {

    /**
     * Create a new ItemRepository instance.
     *
     * @param Item $item
     * @param User $user
     */
    public function __construct(Item $item, User $user = null)
    {
        $this->model = $item;
        if ($user) {
            $this->user = $user;
        }
    }

    /**
     * Create an Item and it's derived class
     *
     * @param $inputs
     * @return Item
     */
    public function store($inputs)
    {
        $item = new Item();
        $item->value = $inputs['value'];
        $item->description = $inputs['description'];
        $item->user_id = $this->user->id;
        $item->save();

        return $item;
    }

    /**
     * Get a paginated result set of Items for a specific User
     *
     * @param $amount int
     * @return mixed
     */
    public function getItemsPaginated($amount)
    {
        return Item::where('user_id', '=', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->simplePaginate($amount);
    }

    /**
     * Get all Items that are associated to the specified Tags
     *
     * @param $tags array
     * @return mixed
     */
    public function itemsForTags($tags)
    {
        $query = Item::whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('name', $tags);
            });

        // Include user constraint if a user is set
        if ($this->user) {
            $query->where('user_id', Auth::user()->id);
        }

        $query->where('itemable_type', 'App\Models\Link')
            ->orderBy('created_at', 'desc')
            ->simplePaginate();

        return $query;
    }
}