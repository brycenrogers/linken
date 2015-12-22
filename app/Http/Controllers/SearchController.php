<?php

namespace App\Http\Controllers;

use App\Interfaces\UserSearchHandlerInterface;
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
    }

    /**
     * Perform a User-based search for any Items that match the search term
     *
     * @param Request $request
     * @param UserSearchHandlerInterface $searchHandler
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function search(Request $request, UserSearchHandlerInterface $searchHandler)
    {
        $query = $request->input('q');
        $items = [];
        $data  = ['items' => $items, 'q' => $query];

        if ($query) {
            try {
                $items = $searchHandler->search($query);
            }
            catch (\Exception $e) {
                $items = [];

            }
            $data['title'] = "Search";
            $data['items'] = $items;
            return view('all', $data);

        } else {

            // Page other than front page was requested, pull from db
            return Response::redirectTo('/');
        }
    }

    /**
     * Delete and rebuild the entire search index
     *
     * @param UserSearchHandlerInterface $searchHandler
     * @return \Illuminate\Http\JsonResponse
     */
    public function reindex(UserSearchHandlerInterface $searchHandler)
    {
        //TODO: Add ACL
        try {
            $searchHandler->reindex();
        }
        catch (\Exception $e) {
            return \Response::json($e->getMessage());
        }

        return \Response::json('Reindex successful');
    }
}