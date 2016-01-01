<?php

namespace App\Interfaces;

/**
 * Interface SearchHandlerInterface
 * @package  App\Interfaces
 * @provider App\Providers\SearchHandlerServiceProvider
 */
interface SearchHandlerInterface
{
    public function search($term, $sortColumn = null, $sortDirection = 'desc', $limit = 50);
    public function reindex();
}