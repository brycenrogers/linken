<?php

namespace App\Interfaces;

interface TagRepositoryInterface
{
    public function search($query);
    public function store($inputs);
}