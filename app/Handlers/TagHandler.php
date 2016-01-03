<?php

namespace App\Handlers;

use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\TagHandlerInterface;
use App\Interfaces\UserCacheHandlerInterface;
use App\Interfaces\UserTagRepositoryInterface;

class TagHandler implements TagHandlerInterface {

    /**
     * Gets Tag objects from cache or DB
     * @param UserCacheHandlerInterface $cacheHandler
     * @param UserTagRepositoryInterface $tagRepo
     * @return array
     */
    public function getTagsForUser(UserCacheHandlerInterface $cacheHandler, UserTagRepositoryInterface $tagRepo)
    {
        // Check to see if there is a cache item
        $tags = $cacheHandler->get(CacheHandlerInterface::TAGS);

        if ( ! $tags) {
            // No tags found in cache, check DB and cache them
            $tags = $tagRepo->all();
            $tagNames = [];

            foreach ($tags as $tag) {
                $tagNames[] = $tag->name;
            }
            $cacheHandler->set(CacheHandlerInterface::TAGS, $tagNames);

            return $tagNames;
        }

        return $tags;
    }

}