<?php

namespace App\Interfaces;

/**
 * Interface CacheHandlerInterface
 * @package  App\Interfaces
 * @provider App\Providers\CacheHandlerServiceProvider
 */
interface CacheHandlerInterface
{
    const MAINPAGE              = 'main.page.';
    const TAGS                  = 'tags.';
    const DISCOVER_TAG          = 'discover.tag.';
    const EXPIRATION            = 1440;

    public function set($type, $value, $uniqueId = null);
    public function get($type, $uniqueId = null);
    public function del($type, $uniqueId = null);
    public function has($type, $uniqueId = null);
}