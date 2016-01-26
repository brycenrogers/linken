<?php

namespace App\Interfaces;

/**
 * Interface SearchHandlerInterface
 * @package  App\Interfaces
 * @provider App\Providers\SearchHandlerServiceProvider
 */
interface SearchHandlerInterface
{
    public function search($term, $user = null, $sortColumn = null, $sortDirection = 'desc', $limit = 50);
    public function basicSearch($term, $user = null, $sortColumn = null, $sortDirection = 'desc', $limit = 50);
    public function filteredSearch($type, $term, $user = null, $sortColumn = null, $sortDirection = 'desc', $limit = 50);
    public function reindex();
    public function add(Searchable $item);
    public function update(Searchable $item);
    public function remove($id);
}