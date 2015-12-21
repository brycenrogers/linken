<?php

namespace App\Libraries;

use App\Interfaces\CacheHandlerInterface;
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
 * @package App\Libraries
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
     * @var \App\Interfaces\CacheHandlerInterface
     */
    protected $cacheHandler;

    /**
     * ItemService constructor
     *
     * @param CacheHandlerInterface $cacheHandler
     * @param UserItemRepositoryInterface $items
     * @param UserLinkRepositoryInterface $links
     * @param UserNoteRepositoryInterface $notes
     * @param UserTagRepositoryInterface $tags
     * @param User $user
     */
    public function __construct(
        CacheHandlerInterface $cacheHandler,
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
                $link->items()->save($item);

                // Save it to the search index
                SearchIndex::upsertToIndex($link);
                break;

            case 'Note':
                // Create the Note
                $note = $this->notesRepo->store($inputs);

                // Associate it to the Item
                $note->items()->save($item);

                // Save it to the search index
                SearchIndex::upsertToIndex($note);
                break;
        }

        // Save Tags
        $tags = $this->tagsRepo->store($inputs);

        // Attach Tags to the Item
        $item->tags()->attach($tags);

        // Reset cache
        $this->cacheHandler->del(CacheHandlerInterface::MAINPAGE);

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
}