<?php

namespace App\Repositories;

use App\Interfaces\NoteRepositoryInterface;
use App\Models\Note;

class NoteRepository extends BaseRepository implements NoteRepositoryInterface {

    /**
     * Create a new ItemRepository instance.
     *
     * @param Note $note
     */
    public function __construct(Note $note)
    {
        $this->model = $note;
    }

    public function store()
    {
        $note = new Note();
        $note->save();
        return $note;
    }

}