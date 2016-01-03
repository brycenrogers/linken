<?php

namespace App\Http\ViewComposers;

use Auth;
use Cache;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Route;

class LinkenViewComposer
{
    /**
     * The user object
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new control panel composer.
     *
     * @param  \App\Models\User $user
     */
    public function __construct(\App\Models\User $user)
    {
        $this->user = Auth::user();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $request = Route::getCurrentRequest();

        $view->with('user', $this->user)->with('requestPath', $request->path());
    }
}