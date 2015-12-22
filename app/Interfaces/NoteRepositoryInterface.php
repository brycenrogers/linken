<?php

namespace App\Interfaces;

/**
 * Interface NoteRepositoryInterface
 * @package App\Interfaces
 * @provider App\Providers\NoteRepositoryServiceProvider
 */
interface NoteRepositoryInterface
{
    public function store();
}