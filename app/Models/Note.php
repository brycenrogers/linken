<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model,
    Spatie\SearchIndex\Searchable;

class Note extends Model implements Searchable
{
    public function items()
    {
        return $this->morphMany('App\Models\Item', 'itemable');
    }

    /**
     * Returns an array with properties which must be indexed.
     *
     * @return array
     */
    public function getSearchableBody()
    {
        /* @var $item \App\Models\Item */
        $item = $this->items()->getResults()->first();
        $tags = $item->tagsAsString();
        $searchableProperties = [
            'user_id' => $item->user()->getResults()->first()->id,
            'value' => $item->value,
            'description' => $item->description,
            'tags' => $tags,
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