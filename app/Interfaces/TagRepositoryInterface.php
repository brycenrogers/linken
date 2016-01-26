<?php

namespace App\Interfaces;

/**
 * Interface TagRepositoryInterface
 * @package App\Interfaces
 * @provider App\Providers\TagRepositoryServiceProvider
 */
interface TagRepositoryInterface
{
    public function all($user = null);
    public function search($query, $user = null);
    public function store($inputs, $user);
    public function recent($count, $user = null);
}