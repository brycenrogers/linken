<?php

namespace App\Repositories;

use App\Interfaces\ItemRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Models\Item;
use App\Models\User;

/**
 * Class ItemRepository
 * @package App\Repositories
 */
class ItemRepository extends BaseRepository implements ItemRepositoryInterface {

    /**
     * Create a new ItemRepository instance.
     *
     * @param Item $item
     */
    public function __construct(Item $item)
    {
        $this->model = $item;
    }

    /**
     * Create an Item and it's derived class
     *
     * @param $inputs
     * @param User $user
     * @return Item
     */
    public function store($inputs, $user)
    {
        $item = new Item();
        $item->value = $inputs['value'];
        $item->description = $inputs['description'];
        $item->user_id = $user->id;
        $item->save();

        return $item;
    }

    /**
     * Get a paginated result set of Items for a specific User
     *
     * @param $amount int
     * @param null|User $user
     * @return \Illuminate\Pagination\Paginator
     */
    public function getItemsPaginated($amount, $user = null)
    {
        $query = Item::with('itemable');

        if ($user) {
            $query->where('user_id', '=', $user->id);
        }

        return $query
            ->orderBy('created_at', 'desc')
            ->simplePaginate($amount);
    }

    /**
     * Get all Items that are associated to the specified Tags
     *
     * @param $tags array
     * @param null|User $user
     * @return mixed
     */
    public function itemsForTags($tags, $user = null)
    {
        $query = Item::with('itemable')->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('name', $tags);
            });

        // Include user constraint if a user is set
        if ($user) {
            $query->where('user_id', $user->id);
        }

        $query->where('itemable_type', 'App\Models\Link')
            ->orderBy('created_at', 'desc');

        return $query->simplePaginate(20);
    }

    /**
     * Get Discovered Tags
     *
     * @param TagRepositoryInterface $tagRepo
     * @param null $user
     * @return mixed
     */
    public function discovered(TagRepositoryInterface $tagRepo, $user = null)
    {
        // Get recent tags
        $tags = $tagRepo->recent(10)->lists('name');

        // Get items for recent tags
        $query = Item::with('itemable')->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('name', $tags);
            })
            ->where('itemable_type', 'App\Models\Link')
            ->orderBy('created_at', 'desc');

        // If we have a user, exclude their tags
        if ($user) {
            $query->where('user_id', '<>', $user->id);
        }

        return $query->simplePaginate();
    }
}