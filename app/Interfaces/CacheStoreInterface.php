<?php

namespace App\Interfaces;

interface CacheStoreInterface {
    public function delete($key);
    public function get($key);
    public function has($key);
    public function set($key, $value);
}