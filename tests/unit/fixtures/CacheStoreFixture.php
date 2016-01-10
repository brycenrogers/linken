<?php

namespace Tests\Unit\Fixtures;

use App\Interfaces\CacheStoreInterface;

class CacheStoreFixture implements CacheStoreInterface {

    public $store;
    public $forget;
    public $get;
    public $has;
    public $put;

    public function store()
    {
        return $this->store;
    }

    public function forget($key)
    {
        return $this->forget;
    }

    public function get($key)
    {
        return $this->get;
    }

    public function has($key)
    {
        return $this->has;
    }

    public function put($key, $value, $expiration)
    {
        return $this->put;
    }
}