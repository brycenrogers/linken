<?php

namespace App\Handlers;

use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\UserCacheHandlerInterface;
use Cache;
use App\Models\User;

class CacheHandler implements CacheHandlerInterface, UserCacheHandlerInterface {

    /**
     * @var \App\Models\User|null
     */
    private $user;

    function __construct(User $user = null)
    {
        if ($user) {
            $this->user = $user;
        }
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
     * @param null $uniqueId
     * @return string
     */
    function getCacheKey($typeConstant, $uniqueId = null)
    {
        if (is_null($uniqueId) && $this->user) {
            $uniqueId = $this->user->id;
        }
        return $typeConstant . $uniqueId;
    }

    /**
     * Delete a key from the cache
     *
     * @param $typeConstant
     * @param null $uniqueId
     * @return bool
     */
    function del($typeConstant, $uniqueId = null)
    {
        return $this->getCacheInstance()->forget($this->getCacheKey($typeConstant, $uniqueId));
    }

    /**
     * Get a value from the cache
     *
     * @param $typeConstant
     * @param null $uniqueId
     * @return mixed
     */
    function get($typeConstant, $uniqueId = null)
    {
        return $this->getCacheInstance()->get($this->getCacheKey($typeConstant, $uniqueId));
    }

    /**
     * Check to see if a value is available in the cache
     *
     * @param $typeConstant
     * @param null $uniqueId
     * @return bool
     */
    function has($typeConstant, $uniqueId = null)
    {
        return $this->getCacheInstance()->has($this->getCacheKey($typeConstant, $uniqueId));
    }

    /**
     * Set a value in the cache
     *
     * @param $typeConstant
     * @param $value
     * @param null $uniqueId
     */
    function set($typeConstant, $value, $uniqueId = null)
    {
        $this->getCacheInstance()->put($this->getCacheKey($typeConstant, $uniqueId), $value, CacheHandlerInterface::EXPIRATION);
    }
}