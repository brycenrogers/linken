<?php

namespace App\Http\Controllers;

use App\Interfaces\LinkParserInterface;
use Illuminate\Http\Request;

class LinkParseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function postLinkParse(Request $request, LinkParserInterface $linkParser)
    {
        $url = $request->input('url');

        // Return image data only if requested, otherwise return all found data
        if ($request->has('image_number')) {
            $data = $linkParser->parseLink($url, $request->input('image_number'));
        } else {
            $data = $linkParser->parseLink($url);
        }

        return response()->json($data);
    }
}