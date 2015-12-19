<?php

namespace App\Repositories;

use App\Models\Link;
use App\Models\User;

/**
 * Class LinkRepository
 * @package App\Repositories
 */
class LinkRepository extends BaseRepository {

    /**
     * Current User instance
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new ItemRepository instance.
     *
     * @param Link $link
     * @param User $user
     */
    public function __construct(Link $link, User $user)
    {
        $this->model = $link;
        $this->user = $user;
    }

    public function store($inputs)
    {
        $link = new Link();
        $link->url = urldecode($inputs['url']);
        $link->photo_url = urldecode($inputs['photo_url']);
        $link->title = $inputs['title'];
        $link->save();

        return $link;
    }

}