<?php

namespace App\Http\Controllers;

class MainController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAll()
    {
        return view('all');
    }
}
