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
        $tags = $this->tags()->get()->toArray();
        $tagNames = [];
        foreach ($tags as $tag) {
            $tagNames[] = $tag['name'];
        }
        return trim(implode($delimiter, $tagNames), $delimiter);
    }
}
