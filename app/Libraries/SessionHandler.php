<?php

namespace App\Libraries;

use Illuminate\Http\Request;

class SessionHandler {

    public static function put(Request $request, $key, $value)
    {
        $request->session()->put($key, $value);
    }

}