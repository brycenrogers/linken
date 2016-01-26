<?php

namespace App\Repositories;

use App\Interfaces\LinkRepositoryInterface;
use App\Models\Link;

/**
 * Class LinkRepository
 * @package App\Repositories
 */
class LinkRepository extends BaseRepository implements LinkRepositoryInterface {

    /**
     * Create a new ItemRepository instance.
     *
     * @param Link $link
     */
    public function __construct(Link $link)
    {
        $this->model = $link;
    }

    public function store($inputs)
    {
        $link = new Link();
        $link->url = urldecode($inputs['url']);
        $link->photo_url = $this->cleanPhotoUrl($inputs['photo_url']);
        $link->title = $inputs['title'];
        $link->save();

        return $link;
    }

    public function cleanPhotoUrl($url)
    {
        $url = urldecode($url);

        // Trim any unwanted characters first
        $url = trim($url, '//');
        $url = trim($url, '/');

        // Check to see if 'http' exists at beginning of string
        $html = substr($url, 0, 4);
        if ($html == 'http') {
            return $url;
        }
        return 'http://' . $url;
    }

}