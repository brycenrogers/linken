<?php

namespace App\Repositories;

use App\Interfaces\LinkRepositoryInterface;
use App\Interfaces\UserLinkRepositoryInterface;
use App\Models\Link;
use App\Models\User;

/**
 * Class LinkRepository
 * @package App\Repositories
 */
class LinkRepository extends BaseRepository implements LinkRepositoryInterface, UserLinkRepositoryInterface {

    /**
     * Create a new ItemRepository instance.
     *
     * @param Link $link
     * @param User $user
     */
    public function __construct(Link $link, User $user = null)
    {
        $this->model = $link;
        if ($user) {
            $this->user = $user;
        }
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