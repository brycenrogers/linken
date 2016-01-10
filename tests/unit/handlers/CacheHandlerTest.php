<?php

namespace Tests\Unit\Handlers;

use TestCase;
use App\Handlers\CacheHandler;
use Tests\Unit\Fixtures\CacheStoreFixture;

/**
 * Class CacheHandlerTest
 * @package Tests\Unit\Handlers
 */
class CacheHandlerTest extends TestCase
{
    /**
     * @var CacheStoreFixture
     */
    private $cacheStore;

    /**
     * @var CacheHandler
     */
    private $cacheHandler;

    public function setUp()
    {
        parent::setUp();
        $this->cacheStore = new CacheStoreFixture();
        $this->cacheHandler = new CacheHandler($this->cacheStore);
    }

    /**
     * getCacheKey() No Unique ID specified for cache key
     */
    public function testGetCacheKey_NoUniqueId()
    {
        $typeConstant = 'test';
        $this->assertEquals('test', $this->cacheHandler->getCacheKey($typeConstant));
    }

    /**
     * getCacheKey() Unique ID specified
     */
    public function testGetCacheKey_UniqueId()
    {
        $typeConstant = 'test';
        $uniqueId = '42';
        $expectedKey = $typeConstant . $uniqueId;
        $this->assertEquals($expectedKey, $this->cacheHandler->getCacheKey($typeConstant, $uniqueId));
    }

    /**
     * del() No Unique ID specified
     */
    public function testDel_NoUniqueId()
    {
        $typeConstant = 'test';
        $this->cacheStore->forget = 'test';
        $this->assertEquals('test', $this->cacheHandler->del($typeConstant));
    }

    /**
     * del() Unique ID specified
     */
    public function testDel_UniqueId()
    {
        $typeConstant = 'test';
        $uniqueId = '42';
        $this->cacheStore->forget = 'test';
        $this->assertEquals('test', $this->cacheHandler->del($typeConstant, $uniqueId));
    }

    /**
     * get() No Unique ID specified
     */
    public function testGet_NoUniqueId()
    {
        $typeConstant = 'test';
        $this->cacheStore->get = 'test';
        $this->assertEquals('test', $this->cacheHandler->get($typeConstant));
    }

    /**
     * get() Unique ID specified
     */
    public function testGet_UniqueId()
    {
        $typeConstant = 'test';
        $uniqueId = '42';
        $this->cacheStore->get = 'test';
        $this->assertEquals('test', $this->cacheHandler->get($typeConstant, $uniqueId));
    }

    /**
     * has() No Unique ID specified
     */
    public function testHas_NoUniqueId()
    {
        $typeConstant = 'test';
        $this->cacheStore->has = true;
        $this->assertEquals(true, $this->cacheHandler->has($typeConstant));
    }

    /**
     * has() Unique ID specified
     */
    public function testHas_UniqueId()
    {
        $typeConstant = 'test';
        $uniqueId = '42';
        $this->cacheStore->has = true;
        $this->assertEquals(true, $this->cacheHandler->has($typeConstant, $uniqueId));
    }

    /**
     * set() No Unique ID specified
     */
    public function testSet_NoUniqueId()
    {
        $typeConstant = 'test';
        $value = 'palpatine';
        $this->cacheStore->put = true;
        $this->assertEquals(true, $this->cacheHandler->set($typeConstant, $value));
    }

    /**
     * set() Unique ID specified
     */
    public function testSet_UniqueId()
    {
        $typeConstant = 'test';
        $value = 'palpatine';
        $uniqueId = '42';
        $this->cacheStore->put = true;
        $this->assertEquals(true, $this->cacheHandler->set($typeConstant, $value, $uniqueId));
    }
}