<?php

namespace App\Providers;

use App\Models\Note;
use Illuminate\Support\ServiceProvider;
use Auth;
use App\Repositories\NoteRepository;

/**
 * Class NoteRepositoryServiceProvider
 * @package App\Providers
 */
class NoteRepositoryServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Interfaces\UserNoteRepositoryInterface', function() {
            return new NoteRepository(new Note(), Auth::user());
        });
        $this->app->bind('App\Interfaces\NoteRepositoryInterface', function() {
            return new NoteRepository(new Note());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'App\Interfaces\NoteRepositoryInterface',
            'App\Interfaces\UserNoteRepositoryInterface'
        ];
    }
}
