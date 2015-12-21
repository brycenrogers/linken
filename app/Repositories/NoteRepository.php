<?php

namespace App\Repositories;

use App\Interfaces\NoteRepositoryInterface;
use App\Interfaces\UserNoteRepositoryInterface;
use App\Models\Note;
use App\Models\User;

class NoteRepository extends BaseRepository implements NoteRepositoryInterface, UserNoteRepositoryInterface {

    /**
     * Create a new ItemRepository instance.
     *
     * @param Note $note
     * @param User $user
     */
    public function __construct(Note $note, User $user = null)
    {
        $this->model = $note;
        if ($user) {
            $this->user = $user;
        }
    }

    public function store()
    {
        $note = new Note();
        $note->save();
        return $note;
    }

}