<?php

namespace App\Interfaces;

interface ItemRepositoryInterface
{
    public function getItemsPaginated($amount);
}