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
        $tags = $item->tagsAsArray();
        $searchableProperties = [
            'user_id' => $item->user()->getResults()->first()->id,
            'value' => $item->value,
            'url' => $this->url,
            'photo' => $this->photo,
            'description' => $item->description,
            'tags' => json_encode($tags),
            'created_at' => $this->created_at->getTimestamp()
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
        return $this->items()->getResults()->first()->id;
    }
}
