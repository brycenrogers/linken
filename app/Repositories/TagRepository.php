<?php

namespace App\Repositories;

use App\Interfaces\TagRepositoryInterface;
use App\Models\Item;
use App\Models\Tag;
use App\Models\User;

/**
 * Class TagRepository
 * @package App\Repositories
 */
class TagRepository extends BaseRepository implements TagRepositoryInterface {

    /**
     * Create a new ItemRepository instance.
     *
     * @param Tag $tag
     */
    public function __construct(Tag $tag)
    {
        $this->model = $tag;
    }

    /**
     * Stores new Tags in the DB
     *
     * @param $inputs
     * @param $user
     * @return array
     */
    public function store($inputs, $user)
    {
        $tags = $inputs['tags'];

        // break into array
        $tags = explode(",", $tags);

        if (is_array($tags)) {
            $tagIds = [];
            foreach ($tags as $tag) {
                $tag = trim($tag);
                if ($tag == "") {
                    continue;
                }
                $newTag = new Tag();
                $newTag = $newTag->firstOrNew(['name' => $tag, 'user_id' => $user->id]);
                if ( ! $newTag->id ) {
                    $newTag->user_id = $user->id;
                    $newTag->save();
                }
                $tagIds[] = $newTag->id;
            }

            return $tagIds;
        }
        return [];
    }

    /**
     * Searches for Tags in the DB based on the query string
     *
     * @param $query
     * @param null|User $user
     * @return mixed
     */
    public function search($query, $user = null)
    {
        $query = Tag::where('name', 'like', $query . "%");
        if ($user) {
            $query->where('user_id', '=', $user->id);
        }
        return $query->get();
    }

    /**
     * Get recently added Tags from the DB based on the specified count
     *
     * @param $count
     * @param null|User $user
     * @return mixed
     */
    public function recent($count, $user = null)
    {
        $query = Tag::query()
            ->orderBy('created_at', 'desc')
            ->take($count);

        if ($user) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    /**
     * Return all Tags from the DB
     *
     * @param null|User $user
     * @return mixed
     */
    public function all($user = null)
    {
        if ($user) {
            return Tag::where('user_id', $user->id)->get();
        }

        return Tag::all();
    }

    /**
     * Update Tags for a specific Item
     *
     * @param Item $item
     * @param array $inputs
     * @param User $user
     */
    public function updateForItem(Item $item, $inputs, $user)
    {
        // Clear out existing Tags
        $item->tags()->detach();

        // If no tags were included, they are clearing out all tags for the item, so just return
        if (empty($inputs['tags'])) {
            return;
        }

        // Save Tags
        $tags = $this->store($inputs, $user);

        // Attach Tags to the Item
        $item->tags()->attach($tags);

        return;
    }
}