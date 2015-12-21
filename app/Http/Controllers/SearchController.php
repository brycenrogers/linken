<?php

namespace App\Http\Controllers;

use App\Libraries\SearchHandler;
use Auth;
use Response;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public $sc;

    public function __construct()
    {
        $this->middleware('auth');
        $this->sc = new SearchHandler(Auth::user());
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        if ($query) {

            //TODO Convert to service provider
            $items = $this->sc->search($query);

            $title = "Search";
            return view('all', ['items' => $items, 'title' => $title, 'q' => $query]);
        } else {
            // Page other than front page was requested, pull from db
            return Response::redirectTo('/');
        }
    }
}