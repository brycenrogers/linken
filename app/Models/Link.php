<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\Searchable;

class Link extends Model implements Searchable
{
    public function item()
    {
        return $this->morphOne('App\Models\Item', 'itemable');
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
        /* @var $item \App\Models\Item */
        $item = $this->item;
        $tags = $item->tagsAsArray();
        $userPhoto = ($item->user->user_photo != null ? $item->user->user_photo : 'new-link.png');
        $searchableProperties = [
            'type' => get_class($this),
            'user_id' => $item->user_id,
            'user_photo' => $userPhoto,
            'value' => $item->value,
            'url' => $this->url,
            'photo' => $this->photo,
            'description' => $item->description,
            'discovery_setting' => $item->discovery_setting,
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
        return intval($this->item->id);
    }
}
