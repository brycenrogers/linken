<?php

namespace App\Handlers;

use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\SearchHandlerInterface;
use App\Interfaces\ItemRepositoryInterface;
use App\Interfaces\LinkRepositoryInterface;
use App\Interfaces\NoteRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\ItemHandlerInterface;
use App\Models\Item;
use Auth;
use Mail;

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
     * The Cache Handler library instance
     *
     * @var \App\Interfaces\CacheHandlerInterface
     */
    protected $cacheHandler;

    /**
     * The Search Handler library instance
     *
     * @var \App\Interfaces\SearchHandlerInterface
     */
    protected $searchHandler;

    /**
     * ItemService constructor
     *
     * @param SearchHandlerInterface $searchHandler
     * @param CacheHandlerInterface $cacheHandler
     * @param ItemRepositoryInterface $items
     * @param LinkRepositoryInterface $links
     * @param NoteRepositoryInterface $notes
     * @param TagRepositoryInterface $tags
     */
    public function __construct(
        SearchHandlerInterface $searchHandler,
        CacheHandlerInterface $cacheHandler,
        ItemRepositoryInterface $items,
        LinkRepositoryInterface $links,
        NoteRepositoryInterface $notes,
        TagRepositoryInterface $tags)
    {
        $this->searchHandler = $searchHandler;
        $this->cacheHandler = $cacheHandler;
        $this->itemsRepo = $items;
        $this->linksRepo = $links;
        $this->notesRepo = $notes;
        $this->tagsRepo = $tags;
    }

    /**
     * Create an Item, its derived class, tags, and reset the main page cache
     *
     * @param $inputs
     * @return Item $item
     */
    public function create($inputs, $user)
    {
        // Create the item
        $item = $this->itemsRepo->store($inputs, $user);

        // Save Tags
        $tags = $this->tagsRepo->store($inputs, $user);

        // Attach Tags to the Item
        $item->tags()->attach($tags);

        // Create the derived type
        switch($inputs['type']) {

            case 'Link':
                // Create the Link
                $link = $this->linksRepo->store($inputs);

                // Associate it to the Item
                $link->item()->save($item);

                // Save it to the search index
                $this->searchHandler->add($link);

                break;

            case 'Note':
                // Create the Note
                $note = $this->notesRepo->store($inputs);

                // Associate it to the Item
                $note->item()->save($item);

                // Save it to the search index
                $this->searchHandler->add($note);

                break;
        }

        // Reset caches
        $this->cacheHandler->del(CacheHandlerInterface::MAINPAGE);
        $this->cacheHandler->del(CacheHandlerInterface::TAGS);

        return $item;
    }

    /**
     * Delete an item
     *
     * @param $id
     */
    public function destroy($id)
    {
        // Delete the item
        $this->itemsRepo->destroy($id);

        // Clear the cache
        $this->cacheHandler->del(CacheHandlerInterface::MAINPAGE);

        // Delete from Search Index
        $this->searchHandler->remove($id);
    }

    public function email($inputs)
    {
        /* @var $item Item */
        $item = $this->itemsRepo->get($inputs['itemId']);

        // Get email addresses
        $emails = $inputs['emails'];

        // Get User
        $user = Auth::user();

        // Send emails
        foreach ($emails as $email) {
            Mail::send('emails.share', ['item' => $item, 'user' => $user], function ($m) use ($item, $user, $email) {
                $m->from($user->email, $user->name);
                $m->to($email, $user->name)->subject($item->value);
            });
        }
    }

    /**
     * Update an item
     *
     * @param $inputs
     * @return bool
     */
    public function update($inputs)
    {
        /* @var $item Item */
        $item = $this->itemsRepo->get($inputs['itemId']);

        $item->value = $inputs['value'];
        $item->description = $inputs['description'];

        $subclass = get_class($item->itemable);

        if ($subclass == 'App\Models\Link') {
            $item->itemable->title = $inputs['value'];
        }

        // Tags
        $this->tagsRepo->updateForItem($item, $inputs, Auth::user());

        // Cache
        $this->cacheHandler->del(CacheHandlerInterface::MAINPAGE);
        $this->cacheHandler->del(CacheHandlerInterface::TAGS);

        // Save
        $item->save();

        // Update Search
        $this->searchHandler->update($item->itemable);

        return $item;
    }
}