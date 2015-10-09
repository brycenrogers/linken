<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
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
}
