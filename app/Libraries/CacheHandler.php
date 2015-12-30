<?php

namespace App\Libraries;

use App\Interfaces\CacheHandlerInterface;
use Cache;
use App\Models\User;

class CacheHandler implements CacheHandlerInterface {

    /**
     * @var \App\Models\User|null
     */
    private $user;

    function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the cache provider
     *
     * @return Cache
     */
    function getCacheInstance()
    {
        return Cache::store('memcached');
    }

    /**
     * Build the cache key based on the type constant and the user's id
     *
     * @param $typeConstant
     * @return string
     */
    function getCacheKey($typeConstant)
    {
        return $typeConstant . $this->user->id;
    }

    /**
     * Delete a key from the cache
     *
     * @param $typeConstant
     * @return bool
     */
    function del($typeConstant)
    {
        return $this->getCacheInstance()->forget($this->getCacheKey($typeConstant));
    }

    /**
     * Get a value from the cache
     *
     * @param $typeConstant
     * @return mixed
     */
    function get($typeConstant)
    {
        return $this->getCacheInstance()->get($this->getCacheKey($typeConstant));
    }

    /**
     * Check to see if a value is available in the cache
     *
     * @param $typeConstant
     * @return bool
     */
    function has($typeConstant)
    {
        return $this->getCacheInstance()->has($this->getCacheKey($typeConstant));
    }

    /**
     * Set a value in the cache
     *
     * @param $typeConstant
     * @param $value
     */
    function set($typeConstant, $value)
    {
        $this->getCacheInstance()->put($this->getCacheKey($typeConstant), $value, CacheHandlerInterface::EXPIRATION);
    }
}