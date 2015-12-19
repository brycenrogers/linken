<?php

namespace App\Repositories;

use App\Interfaces\ItemRepositoryInterface,
    App\Models\Item,
    App\Models\User;

/**
 * Class ItemRepository
 * @package App\Repositories
 */
class ItemRepository extends BaseRepository implements ItemRepositoryInterface {

    /**
     * The Item instance
     *
     * @var \App\Models\Item
     */
    protected $item;

    /**
     * Current User instance
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new ItemRepository instance.
     *
     * @param Item $item
     * @param User $user
     */
    public function __construct(Item $item, User $user)
    {
        $this->model = $item;
        $this->user = $user;
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
}