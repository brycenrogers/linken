<?php

namespace App\Interfaces;

interface ItemRepositoryInterface
{
    public function destroy($id);
    public function store($inputs);
    public function getItemsPaginated($amount);
    public function itemsForTags($tags);
}