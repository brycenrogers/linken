<?php

namespace App\Libraries;

use Cache;
use App\Interfaces\CacheStoreInterface;

/**
 * Class CacheStore
 *
 * This library defines the default cache store provider for Linken
 *
 * @package App\Libraries
 */
class CacheStore implements CacheStoreInterface {

    public function store()
    {
        return Cache::store('memcached');
    }

    public function forget($key)
    {
        return $this->store()->forget($key);
    }

    public function get($key)
    {
        return $this->store()->get($key);
    }

    public function has($key)
    {
        return $this->store()->has($key);
    }

    public function put($key, $value, $expiration)
    {
        return $this->store()->put($key, $value, $expiration);
    }
}