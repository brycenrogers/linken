<?php

namespace App\Repositories;

use App\Models\Note;
use App\Models\User;

class NoteRepository extends BaseRepository {

    /**
     * Current User instance
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new ItemRepository instance.
     *
     * @param Note $note
     * @param User $user
     */
    public function __construct(Note $note, User $user)
    {
        $this->model = $note;
        $this->user = $user;
    }

    public function store()
    {
        $note = new Note();
        $note->save();
        return $note;
    }

}