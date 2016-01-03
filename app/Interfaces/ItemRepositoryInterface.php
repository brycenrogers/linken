<?php

namespace App\Interfaces;

/**
 * Interface ItemRepositoryInterface
 * @package App\Interfaces
 * @provider App\Providers\ItemRepositoryServiceProvider
 */
interface ItemRepositoryInterface
{
    public function get($id, $with = []);
    public function destroy($id);
    public function store($inputs);
    public function getItemsPaginated($amount);
    public function itemsForTags($tags);
}