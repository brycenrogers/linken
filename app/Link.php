<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    public function items()
    {
        return $this->morphMany('App\Item', 'itemable');
    }
}
