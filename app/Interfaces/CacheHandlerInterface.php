<?php

namespace App\Interfaces;

interface CacheHandlerInterface
{
    const MAINPAGE      = 'main.page.';
    const TAGS          = 'tags.';
    const EXPIRATION    = 1440;
    public function set($type, $value);
    public function get($type);
    public function del($type);
    public function has($type);
}