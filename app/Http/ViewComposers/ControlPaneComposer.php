<?php

namespace App\Http\ViewComposers;

use Auth;
use Illuminate\Contracts\View\View;

class ControlPaneComposer
{
    /**
     * The user object
     *
     * @var \App\User
     */
    protected $user;

    /**
     * Create a new control panel composer.
     *
     * @param  \App\User $user
     */
    public function __construct(\App\User $user)
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
        $view->with('user', $this->user);
    }
}