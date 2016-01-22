<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class HelpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = "Help";
        return view('help', ['title' => $title]);
    }
}