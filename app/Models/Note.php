<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\Searchable;

class Note extends Model implements Searchable
{
    public function item()
    {
        return $this->morphOne('App\Models\Item', 'itemable');
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
        $tags = $item->tagsAsString();
        $userPhoto = ($item->user->user_photo != null ? $item->user->user_photo : 'new-link.png');
        $searchableProperties = [
            'type' => get_class($this),
            'user_id' => $item->user->id,
            'value' => $item->value,
            'description' => $item->description,
            'tags' => json_encode($tags),
            'url' => null,
            'photo' => null,
            'user_photo' => $userPhoto,
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
        return $this->item->id;
    }
}
