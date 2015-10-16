<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function itemable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    /**
     * Returns a string containing all tags for the Item, separated by $delimiter
     * @param string $delimiter
     * @return string
     */
    public function tagsAsString($delimiter = ' ')
    {
        return trim(implode($delimiter, $this->tagsAsArray()), $delimiter);
    }

    /**
     * Returns an array containing all tag names for Item
     * @return array
     */
    public function tagsAsArray()
    {
        $tags = $this->tags()->get()->toArray();
        $tagNames = [];
        foreach ($tags as $tag) {
            $tagNames[] = $tag['name'];
        }
        return $tagNames;
    }
}
