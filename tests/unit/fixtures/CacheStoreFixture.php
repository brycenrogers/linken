<?php

namespace Tests\Unit\Fixtures;

use App\Interfaces\CacheStoreInterface;

class CacheStoreFixture implements CacheStoreInterface {

    public $delete;
    public $get;
    public $has;
    public $set;

    public function delete($key)
    {
        return $this->delete;
    }

    public function get($key)
    {
        return $this->get;
    }

    public function has($key)
    {
        return $this->has;
    }

    public function set($key, $value)
    {
        return $this->set;
    }
}