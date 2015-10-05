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

        if ($request->input('image')) {

            // Only image was requested
            $num = intval($request->input('image_number'));
            $images = $parser->getImageSources();
            if ($images && array_key_exists($num, $images)) {
                $imageUrl = $images[$num];
                $num++;
            } else if ($images) {
                $imageUrl = $images[0];
                $num = 0;
            }

            return response()->json(['image' => $imageUrl, 'image_number' => $num]);
        }

        $imageCount = 0;

        // Pull images from Facebook info first, if available
        foreach ($metaTags as $metaTagArray) {
            $key = strtolower($metaTagArray[0]);
            $value = $metaTagArray[1];
            if($key == 'og:title') {
                $title = $value;
            } elseif ($key == 'og:image') {
                $imageUrl = $value;
                $imageCount++;
            }
        }

        // Still no image, get it from first available source
        if ($imageUrl == "") {
            $images = $parser->getImageSources();
            if ($images) {
                $imageUrl = $images[0];
                $imageCount = $imageCount + count($images);
            }
        }

        // Still no title, get it from the page
        if ($title == "") {
            $title = $parser->getTitle(true);
        }

        return response()->json(['title' => $title, 'image' => $imageUrl, 'image_count' => $imageCount]);
    }
}