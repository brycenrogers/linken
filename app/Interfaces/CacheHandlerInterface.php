<?php

namespace App\Interfaces;

interface CacheHandlerInterface
{
    const MAINPAGE  = 'main.page.';
    const TAGS      = 'tags.';
    public function set($type, $value, $userId);
    public function get($type, $userId);
    public function del($type, $userId);
    public function has($type, $userId);
}