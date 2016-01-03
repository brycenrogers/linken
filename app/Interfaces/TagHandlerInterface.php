<?php

namespace App\Interfaces;

/**
 * Interface TagHandlerInterface
 * @package App\Interfaces
 * @provider App\Providers\TagHandlerServiceProvider
 */
interface TagHandlerInterface
{
    public function getTagsForUser(UserCacheHandlerInterface $cacheHandler, UserTagRepositoryInterface $tagRepo);
}