<?php

namespace Tests\Unit\Fixtures;

use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\UserTagRepositoryInterface;

class TagRepositoryFixture implements TagRepositoryInterface, UserTagRepositoryInterface
{

    public $all;
    public $search;
    public $store;
    public $recent;

    public function all()
    {
        return $this->all;
    }

    public function search($query)
    {
        return $this->search;
    }

    public function store($inputs)
    {
        return $this->store;
    }

    public function recent($count)
    {
        return $this->recent;
    }
}