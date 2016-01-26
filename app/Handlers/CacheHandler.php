<?php

namespace App\Handlers;

use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\CacheStoreInterface;
use Auth;

class CacheHandler implements CacheHandlerInterface {

    /**
     * @var $cacheStore CacheStoreInterface
     */
    private $cacheStore;

    /**
     * CacheHandler constructor.
     * @param CacheStoreInterface $cacheStore
     */
    function __construct(CacheStoreInterface $cacheStore)
    {
        $this->cacheStore = $cacheStore;
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
        if (is_null($uniqueId)) {
            $uniqueId = Auth::user()->id;
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
        return $this->cacheStore->forget($this->getCacheKey($typeConstant, $uniqueId));
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
        return $this->cacheStore->get($this->getCacheKey($typeConstant, $uniqueId));
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
        return $this->cacheStore->has($this->getCacheKey($typeConstant, $uniqueId));
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
        return $this->cacheStore->put($this->getCacheKey($typeConstant, $uniqueId), $value, CacheHandlerInterface::EXPIRATION);
    }
}