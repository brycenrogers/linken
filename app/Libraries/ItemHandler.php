<?php

namespace App\Libraries;

use App\Interfaces\CacheHandlerInterface,
    App\Models\User,
    App\Repositories\ItemRepository,
    App\Repositories\LinkRepository,
    App\Repositories\NoteRepository,
    App\Repositories\TagRepository,
    App\Interfaces\ItemHandlerInterface,
    App\Models\Item;

/**
 * Class ItemHandler
 * @package App\Libraries
 */
class ItemHandler implements ItemHandlerInterface {

    /**
     * The Item Repository instance
     *
     * @var ItemRepository
     */
    protected $items;

    /**
     * The Link Repository instance
     *
     * @var ItemRepository
     */
    protected $links;

    /**
     * The Note Repository instance
     *
     * @var ItemRepository
     */
    protected $notes;

    /**
     * The Tags Repository instance
     *
     * @var TagRepository
     */
    protected $tags;

    /**
     * The current User
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * The Cache Handler instance
     *
     * @var \App\Interfaces\CacheHandlerInterface
     */
    protected $cacheHandler;

    /**
     * ItemService constructor
     *
     * @param CacheHandlerInterface $cacheHandler
     * @param ItemRepository $items
     * @param LinkRepository $links
     * @param NoteRepository $notes
     * @param TagRepository $tags
     * @param User $user
     */
    public function __construct(
        CacheHandlerInterface $cacheHandler,
        ItemRepository $items,
        LinkRepository $links,
        NoteRepository $notes,
        TagRepository $tags,
        User $user)
    {
        $this->cacheHandler = $cacheHandler;
        $this->items = $items;
        $this->links = $links;
        $this->notes = $notes;
        $this->tags = $tags;
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
        $item = $this->items->store($inputs);

        // Create the derived type
        switch($inputs['type']) {
            case 'Link':
                // Create the Link
                $link = $this->links->store($inputs);

                // Associate it to the Item
                $link->items()->save($item);

                // Save it to the search index
                \SearchIndex::upsertToIndex($link);
                break;
            case 'Note':
                // Create the Note
                $note = $this->notes->store($inputs);

                // Associate it to the Item
                $note->items()->save($item);

                // Save it to the search index
                \SearchIndex::upsertToIndex($note);
                break;
        }

        // Save tags
        $tags = $this->tags->store($inputs, $this->user->id);

        // Attach them to the Item
        $item->tags()->attach($tags);

        // Reset cache
        $this->cacheHandler->del(CacheHandlerInterface::MAINPAGE);

        return $item;
    }
}