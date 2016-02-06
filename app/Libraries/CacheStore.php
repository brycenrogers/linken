<?php

namespace App\Libraries;

use Redis;
use App\Interfaces\CacheStoreInterface;

/**
 * Class CacheStore
 *
 * This library defines the default cache store provider for Linken
 *
 * @package App\Libraries
 */
class CacheStore implements CacheStoreInterface {

    public function delete($key)
    {
        return Redis::del($key);
    }

    public function get($key)
    {
        return Redis::get($key);
    }

    public function has($key)
    {
        return Redis::exists($key);
    }

    public function set($key, $value)
    {
        return Redis::set($key, $value);
    }
}