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

    public function search($query)
    {
        $query = Tag::where('name', 'like', $query . "%");
        if ($this->user) {
            $query->where('user_id', '=', $this->user->id);
        }
        return $query->get();
    }

    /**
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

}