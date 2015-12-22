<?php

namespace App\Interfaces;

/**
 * Interface TagRepositoryInterface
 * @package App\Interfaces
 * @provider App\Providers\TagRepositoryServiceProvider
 */
interface TagRepositoryInterface
{
    public function search($query);
    public function store($inputs);
}