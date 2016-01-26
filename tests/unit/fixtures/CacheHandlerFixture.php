<?php

namespace Tests\Unit\Fixtures;

use App\Interfaces\CacheHandlerInterface;

class CacheHandlerFixture implements CacheHandlerInterface {

    public $set;
    public $get;
    public $del;
    public $has;

    public function set($type, $value, $uniqueId = null)
    {
        return $this->set;
    }

    public function get($type, $uniqueId = null)
    {
        return $this->get;
    }

    public function del($type, $uniqueId = null)
    {
        return $this->del;
    }

    public function has($type, $uniqueId = null)
    {
        return $this->has;
    }
}