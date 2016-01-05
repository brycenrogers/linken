<?php

namespace App\Handlers;

use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\SearchHandlerInterface;
use App\Interfaces\TagHandlerInterface;
use App\Interfaces\UserCacheHandlerInterface;
use App\Interfaces\UserTagRepositoryInterface;
use App\Models\Tag;
use Auth;
use DB;

/**
 * Handles Discover Cache functionality
 *
 * Class DiscoverCacheHandler
 * @package App\Handlers
 */
class DiscoverCacheHandler
{
    public function getLinksForUser
    (
        UserTagRepositoryInterface $tagRepo,
        TagHandlerInterface $tagHandler,
        UserCacheHandlerInterface $cacheHandler,
        $tag = null
    ) {
        // Get user's tags
        $tags = $tagHandler->getTagsForUser($cacheHandler, $tagRepo);

        // Find Discover Cache items for the user's Tags
        $items = [];
        foreach ($tags as $tag) {
            $items[$tag] = $cacheHandler->get(CacheHandlerInterface::DISCOVER_TAG, $tag);
        }

        // Filter out their own links
        foreach ($items as $tag => $itemArray) {
            if (isset($itemArray)) {
                foreach ($itemArray as $key => $item) {
                    if ($item->user_id == Auth::user()->id) {
                        unset($items[$tag][$key]);
                    }
                }
                // If all items are filtered out, remove them from the list
                if (count($items[$tag]) === 0) {
                    unset($items[$tag]);
                }
            }
        }

        return $items;
    }

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
        $links = $searchHandler->filteredSearch('tags', $tag->name, 'created_at', 'desc', 10);
        $cacheHandler->set(CacheHandlerInterface::DISCOVER_TAG, $links, $tag->name);
    }
}