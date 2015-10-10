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
        $imageSources = [];
        $imageCount = 0;

        // Pull images from Facebook info first, if available
        foreach ($metaTags as $metaTagArray) {
            $key = strtolower($metaTagArray[0]);
            $value = $metaTagArray[1];
            if($key == 'og:title') {
                // Grab a possible title while we're in here
                $title = $value;
            } elseif ($key == 'og:image') {
                if ($value != 'default') {
                    $imageSources[] = $value;
                    $imageCount++;
                }
            }
        }

        $num = 0;
        if ($request->has('image_number')) {
            $num = intval($request->input('image_number'));
            $num++;
        }

        if (array_key_exists($num, $imageSources)) {
            $imageUrl = $imageSources[$num];
        } else {

            // Either no images have been found yet, or the requested image was not found in the array of fb images,
            // so check other sources
            $images = $parser->getImageSources();

            if (!$images) {
                // No additional images were found, reset to original image
                $imageUrl = $imageSources[0];
                $num = 0;
            } else {

                // More images were found, find the requested one
                foreach ($images as $imageSource) {
                    $imageSources[] = $imageSource;
                }

                if ($imageSources && array_key_exists($num, $imageSources)) {
                    $imageUrl = $imageSources[$num];
                } else {
                    // Requested image was not found, reset to original image
                    $imageUrl = $imageSources[0];
                    $num = 0;
                }
            }
        }

        // Check to make sure the image url has the base url in it, otherwise its a relative url. If thats the case
        // update the imageUrl to include the base url.

        $protocolsFound = [];
        preg_match('/(http:\/\/|https:\/\/)/i', $imageUrl, $protocolsFound);
        if (count($protocolsFound) === 0) {
            if (substr($imageUrl, 0, 2) == '//') {
                $imageUrl = 'http:' . $imageUrl;
            } else if (substr($imageUrl, 0, 1) == '/') {
                $imageUrl = $parser->base_url . substr($imageUrl, 1);
            } else {
                $imageUrl = $parser->base_url . $imageUrl;
            }
        }

        // Only image info was requested
        if ($request->has('image')) {
            return response()->json(['image' => $imageUrl, 'image_number' => $num]);
        }

        // Still no title, get it from the page
        if ($title == "") {
            $title = $parser->getTitle(true);
        }

        return response()->json(['title' => $title, 'image' => $imageUrl, 'image_count' => $imageCount]);
    }
}