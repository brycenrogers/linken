<?php

namespace App\Http\Controllers;

use App\Libraries\WebsiteParser;
use Illuminate\Http\Request;

class LinkParseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function postLinkParse(Request $request)
    {
        $url = $request->input('url');

        // Parse the URL
        $parser = new WebsiteParser($url);

        // Check for Facebook meta tags for appropriate image and title
        $metaTags = $parser->getMetaTags(true);

        $title = "";
        $imageUrl = "";

        foreach ($metaTags as $metaTagArray) {
            $key = strtolower($metaTagArray[0]);
            $value = $metaTagArray[1];
            if($key == 'og:title') {
                $title = $value;
            } elseif ($key == 'og:image') {
                $imageUrl = $value;
            }
        }

        if ($imageUrl == "") {
            $images = $parser->getImageSources();
            if ($images) {
                $imageUrl = $images[0];
            }
        }

        if ($title == "") {
            $title = $parser->getTitle(true);
        }

        return response()->json(['title' => $title, 'image' => $imageUrl]);
    }
}