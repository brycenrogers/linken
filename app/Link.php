<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\SearchIndex\Searchable;

class Link extends Model implements Searchable
{
    public function items()
    {
        return $this->morphMany('App\Item', 'itemable');
    }

    /**
     * Determines if there is a proper protocol on the URL and appends http:// if none are found
     *
     * @param  string  $url
     * @return string
     */
    public function setUrlAttribute($url)
    {
        $protocolsFound = [];
        preg_match('/(http:\/\/|https:\/\/|ftp:\/\/|sftp:\/\/)/i', $url, $protocolsFound);
        if (count($protocolsFound) === 0) {
            $url = 'http://' . $url;
        }
        $this->attributes['url'] = $url;
    }

    /**
     * Returns an array with properties which must be indexed.
     *
     * @return array
     */
    public function getSearchableBody()
    {
        /* @var $item \App\Item */
        $item = $this->items()->getResults()->first();
        $tags = $item->tagsAsString();
        $searchableProperties = [
            'user_id' => $item->user()->getResults()->first()->id,
            'title' => $this->title,
            'url' => $this->url,
            'description' => $this->description,
            'tags' => $tags
        ];
        return $searchableProperties;
    }

    /**
     * Return the type of the searchable subject.
     *
     * @return string
     */
    public function getSearchableType()
    {
        return 'item';
    }

    /**
     * Return the id of the searchable subject.
     *
     * @return string
     */
    public function getSearchableId()
    {
        return $this->id;
    }
}
