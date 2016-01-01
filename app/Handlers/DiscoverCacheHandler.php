<?php

namespace App\Handlers;

use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\SearchHandlerInterface;
use App\Models\Tag;
use DB;

/**
 * Builds the cache data necessary for the Discover feature
 *
 * Class DiscoverCacheHandler
 * @package App\Handlers
 */
class DiscoverCacheHandler
{
    /**
     * Generate or regenerate all Discover Cache items
     *
     * @param SearchHandlerInterface $searchHandler
     * @param CacheHandlerInterface $cacheHandler
     */
    public function generateAll(SearchHandlerInterface $searchHandler, CacheHandlerInterface $cacheHandler) {

        // Find all links with photos URLs that need thumbnails
        DB::table('tags')->groupBy('name')->chunk(100, function($tags) use ($searchHandler, $cacheHandler) {

            /* @var $link \App\Models\Tag */
            foreach ($tags as $tag) {
                // Add them to the cache
                $this->generate($tag, $cacheHandler, $searchHandler);
            }
        });
    }

    /**
     * Generate or regenerate popular Discover Cache items
     *
     * @param SearchHandlerInterface $searchHandler
     * @param CacheHandlerInterface $cacheHandler
     */
    public function generatePopular(SearchHandlerInterface $searchHandler, CacheHandlerInterface $cacheHandler) {
        $tags = Tag::all()->groupBy('name')->orderBy('created_at')->take(20)->get();
        foreach ($tags as $tag) {
            $this->generate($tag, $cacheHandler, $searchHandler);
        }
    }

    /**
     * Generate the cache item for a specific Tag with the given Links
     *
     * @param $tag
     * @param CacheHandlerInterface $cacheHandler
     * @param SearchHandlerInterface $searchHandler
     * @internal param $links
     */
    private function generate($tag, CacheHandlerInterface $cacheHandler, SearchHandlerInterface $searchHandler)
    {
        // Find items for the requested Tag
        $links = $searchHandler->search($tag->name, 'created_at', 'desc', 10);
        $cacheHandler->set(CacheHandlerInterface::DISCOVER_TAG, $links, $tag->name);
    }
}