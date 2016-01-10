<?php

namespace App\Interfaces;

interface CacheStoreInterface {
    public function store();
    public function forget($key);
    public function get($key);
    public function has($key);
    public function put($key, $value, $expiration);
}