<?php

namespace App\Interfaces;

/**
 * Interface LinkRepositoryInterface
 * @package App\Interfaces
 * @provider App\Providers\LinkRepositoryServiceProvider
 */
interface LinkRepositoryInterface
{
    public function store($inputs);
}