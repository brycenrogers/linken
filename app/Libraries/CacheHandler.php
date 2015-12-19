<?php

namespace App\Libraries;

use App\Interfaces\CacheHandlerInterface,
    App\Models\User,
    Cache;

class CacheHandler implements CacheHandlerInterface {

    function getCacheInstance()
    {
        return Cache::store('memcached');
    }

    function getCacheKey($typeConstant, $userId)
    {
        return $typeConstant . $userId;
    }

    function del($typeConstant, $userId)
    {
        return $this->getCacheInstance()->forget($this->getCacheKey($typeConstant, $userId));
    }

    function get($typeConstant, $userId)
    {
        return $this->getCacheInstance()->get($this->getCacheKey($typeConstant, $userId));
    }

    function has($typeConstant, $userId)
    {
        return $this->getCacheInstance()->has($this->getCacheKey($typeConstant, $userId));
    }

    function set($typeConstant, $value, $userId)
    {
        return $this->getCacheInstance()->put($this->getCacheKey($typeConstant, $userId));
    }
}