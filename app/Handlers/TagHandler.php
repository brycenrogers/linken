<?php

namespace App\Handlers;

use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\TagHandlerInterface;
use App\Interfaces\TagRepositoryInterface;

class TagHandler implements TagHandlerInterface {

    /**
     * Gets Tag objects from cache or DB
     * @param CacheHandlerInterface $cacheHandler
     * @param TagRepositoryInterface $tagRepo
     * @return array
     */
    public function getTagsForUser(CacheHandlerInterface $cacheHandler, TagRepositoryInterface $tagRepo)
    {
        // Check to see if there is a cache item
        $tags = $cacheHandler->get(CacheHandlerInterface::TAGS);

        // No tags found in cache, check DB, cache them, and return them
        if ( ! $tags) {
            $tags = $tagRepo->all(Auth::user());
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