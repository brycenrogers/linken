<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Models\User;

class TagRepository extends BaseRepository {

    /**
     * Current User instance
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new ItemRepository instance.
     *
     * @param Tag $tag
     * @param User $user
     */
    public function __construct(Tag $tag, User $user)
    {
        $this->model = $tag;
        $this->user = $user;
    }

    public function store($inputs, $userId)
    {
        $tags = $inputs['tags'];
        $tags = explode("|", $tags);
        $tagIds = [];
        foreach ($tags as $tag) {
            if ($tag == "") {
                continue;
            }
            $newTag = new Tag();
            $newTag = $newTag->firstOrNew(['name' => $tag, 'user_id' => $userId]);
            if ( ! $newTag->id ) {
                $newTag->user_id = $userId;
                $newTag->save();
            }
            $tagIds[] = $newTag->id;
        }

        return $tagIds;
    }

}