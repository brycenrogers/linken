<?php

namespace App\Repositories;

use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\UserTagRepositoryInterface;
use App\Models\Tag;
use App\Models\User;

/**
 * Class TagRepository
 * @package App\Repositories
 */
class TagRepository extends BaseRepository implements TagRepositoryInterface, UserTagRepositoryInterface {

    /**
     * Create a new ItemRepository instance.
     *
     * @param Tag $tag
     * @param User $user
     */
    public function __construct(Tag $tag, User $user = null)
    {
        $this->model = $tag;
        if ($user) {
            $this->user = $user;
        }
    }

    /**
     * Stores new Tags in the DB
     *
     * @param $inputs
     * @return array
     */
    public function store($inputs)
    {
        $tags = $inputs['tags'];
        $tags = explode("|", $tags);
        $tagIds = [];
        foreach ($tags as $tag) {
            if ($tag == "") {
                continue;
            }
            $newTag = new Tag();
            $newTag = $newTag->firstOrNew(['name' => $tag, 'user_id' => $this->user->id]);
            if ( ! $newTag->id ) {
                $newTag->user_id = $this->user->id;
                $newTag->save();
            }
            $tagIds[] = $newTag->id;
        }

        return $tagIds;
    }

    /**
     * Searches for Tags in the DB based on the query string
     *
     * @param $query
     * @return mixed
     */
    public function search($query)
    {
        $query = Tag::where('name', 'like', $query . "%");
        if ($this->user) {
            $query->where('user_id', '=', $this->user->id);
        }
        return $query->get();
    }

    /**
     * Get recently added Tags from the DB based on the specified count
     *
     * @param $count
     * @return mixed
     */
    public function recent($count)
    {
        $query = Tag::query()
            ->orderBy('created_at', 'desc')
            ->take($count);

        if ($this->user) {
            $query->where('user_id', $this->user->id);
        }

        return $query;
    }

    /**
     * Return all Tags from the DB
     *
     * @return mixed
     */
    public function all()
    {
        if ($this->user) {
            return Tag::where('user_id', $this->user->id)->get();
        }

        return Tag::all();
    }
}