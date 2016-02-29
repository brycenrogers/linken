<?php

namespace App\Listeners;

use Auth;
use Illuminate\Auth\Events\Login;

class SuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = Auth::user();
        request()->session()->put('discovery_setting', $user->discovery_setting);
    }
}
