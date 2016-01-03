<?php

namespace App\Handlers;

use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\UserCacheHandlerInterface;
use App\Models\User;
use App\Interfaces\UserItemRepositoryInterface;
use App\Interfaces\UserLinkRepositoryInterface;
use App\Interfaces\UserNoteRepositoryInterface;
use App\Interfaces\UserTagRepositoryInterface;
use App\Interfaces\ItemHandlerInterface;
use App\Models\Item;
use SearchIndex;

/**
 * Class ItemHandler
 *
 * This class has access to all Repositories and the CacheHandler
 *
 * @package App\Handlers
 */
class ItemHandler implements ItemHandlerInterface {

    /**
     * The Item Repository instance
     *
     * @var \App\Repositories\ItemRepository
     */
    protected $itemsRepo;

    /**
     * The Link Repository instance
     *
     * @var \App\Repositories\LinkRepository
     */
    protected $linksRepo;

    /**
     * The Note Repository instance
     *
     * @var \App\Repositories\NoteRepository
     */
    protected $notesRepo;

    /**
     * The Tags Repository instance
     *
     * @var \App\Repositories\TagRepository
     */
    protected $tagsRepo;

    /**
     * The current User
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * The Cache Handler library instance
     *
     * @var \App\Interfaces\UserCacheHandlerInterface
     */
    protected $cacheHandler;

    /**
     * ItemService constructor
     *
     * @param UserCacheHandlerInterface $cacheHandler
     * @param UserItemRepositoryInterface $items
     * @param UserLinkRepositoryInterface $links
     * @param UserNoteRepositoryInterface $notes
     * @param UserTagRepositoryInterface $tags
     * @param User $user
     */
    public function __construct(
        UserCacheHandlerInterface $cacheHandler,
        UserItemRepositoryInterface $items,
        UserLinkRepositoryInterface $links,
        UserNoteRepositoryInterface $notes,
        UserTagRepositoryInterface $tags,
        User $user)
    {
        $this->cacheHandler = $cacheHandler;
        $this->itemsRepo = $items;
        $this->linksRepo = $links;
        $this->notesRepo = $notes;
        $this->tagsRepo = $tags;
        $this->user = $user;
    }

    /**
     * Create an Item, its derived class, tags, and reset the main page cache
     *
     * @param $inputs
     * @return Item $item
     */
    public function create($inputs)
    {
        // Create the item
        $item = $this->itemsRepo->store($inputs);

        // Create the derived type
        switch($inputs['type']) {

            case 'Link':
                // Create the Link
                $link = $this->linksRepo->store($inputs);

                // Associate it to the Item
                $link->item()->save($item);

                // Save it to the search index
                SearchIndex::upsertToIndex($link);
                break;

            case 'Note':
                // Create the Note
                $note = $this->notesRepo->store($inputs);

                // Associate it to the Item
                $note->item()->save($item);

                // Save it to the search index
                SearchIndex::upsertToIndex($note);
                break;
        }

        // Save Tags
        $tags = $this->tagsRepo->store($inputs);

        // Attach Tags to the Item
        $item->tags()->attach($tags);

        // Reset caches
        $this->cacheHandler->del(CacheHandlerInterface::MAINPAGE);
        $this->cacheHandler->del(CacheHandlerInterface::TAGS);

        return $item;
    }

    public function destroy($id)
    {
        // Delete the item
        $this->itemsRepo->destroy($id);

        // Clear the cache
        $this->cacheHandler->del(CacheHandlerInterface::MAINPAGE);

        // Delete from Search Index
        SearchIndex::removeFromIndexByTypeAndId('item', $id);
    }

    /**
     * Update an item
     *
     * @param $inputs
     * @return bool
     */
    public function update($inputs)
    {
        $item = $this->itemsRepo->get($inputs['itemId']);

        $item->value = $inputs['value'];
        $item->description = $inputs['description'];

        $subclass = get_class($item->itemable);

        if ($subclass == 'App\Models\Link') {
            $item->itemable->title = $inputs['value'];
        }

        // Tags

        // Cache
        $this->cacheHandler->del(CacheHandlerInterface::MAINPAGE);

        $item->save();

        return $item;
    }
}