<?php

namespace Tests\Unit\Fixtures;

use App\Interfaces\TagRepositoryInterface;

class TagRepositoryFixture implements TagRepositoryInterface
{

    public $all;
    public $search;
    public $store;
    public $recent;

    public function all($user = null)
    {
        return $this->all;
    }

    public function search($query, $user = null)
    {
        return $this->search;
    }

    public function store($inputs, $user)
    {
        return $this->store;
    }

    public function recent($count, $user = null)
    {
        return $this->recent;
    }
}