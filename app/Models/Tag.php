<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['name', 'user', 'item'];

    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function items()
    {
        return $this->belongsToMany('App\Models\Item');
    }
}
