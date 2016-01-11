<?php

namespace App\Handlers;

use App\Interfaces\LinkParserInterface;
use App\Libraries\WebsiteParser;

class LinkParserHandler implements LinkParserInterface {

    /**
     * Parse the given $url and return an array of the found data. Only send image if $imageNumber requested.
     *
     * @param $url
     * @param null $imageNumber
     * @return array
     */
    public function parseLink($url, $imageNumber = null)
    {
        // If its an image link, either find the appropriate URL for it's metadata or just return the link
        $imageUrl = null;
        $knownUrl = $this->cleanKnownUrl($url);

        if (is_null($knownUrl)) {
            // Not a known url, check to see if its just an image
            $imageUrl = $this->handleImageLinks($url);
        } else {
            // Change the main URL to the known URL
            $url = $knownUrl;
        }

        // Parse the URL
        $parser = new WebsiteParser($url);

        // Check for Facebook meta tags for appropriate image and title
        $metaTags = $parser->getMetaTags(true);

        $title = "";
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

        // No image was found in previous checks, so begin checking other sources
        if (is_null($imageUrl)) {

            // If an $imageNumber is specified, they are looping through possible options
            if ( ! is_null($imageNumber)) {
                $num = intval($imageNumber);
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
                    if (count($imageSources)) {
                        $imageUrl = $imageSources[0];
                    } else {
                        $imageUrl = null;
                    }
                    $num = 0;
                } else {

                    // More images were found, find the requested one
                    foreach ($images as $imageSource) {
                        $imageSources[] = $imageSource;
                    }

                    if ($imageSources && array_key_exists($num, $imageSources)) {
                        $imageUrl = $imageSources[$num];
                    } else {
                        if (count($imageSources) === 0) {
                            $imageUrl = "";
                        } else {
                            // Requested image was not found, reset to original image
                            $imageUrl = $imageSources[0];
                            $num = 0;
                        }
                    }
                }
            }

            // Check to make sure the image url has the base url in it, otherwise its a relative url. If thats the case
            // update the imageUrl to include the base url.
            if ($imageUrl != "") {
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
            }
        }

        // Still no imageUrl, so load the default
        if (is_null($imageUrl)) {
            $imageUrl = asset('assets/images/new-link.png');
        }

        // Only image info was requested
        if ( ! is_null($imageNumber)) {
            return ['image' => $imageUrl, 'image_number' => $num];
        }

        // Still no title, get it from the page
        if ($title == "") {
            $title = $parser->getTitle(true);

            // If no title was found, return the name of the file
            if ($title == "") {
                $pathInfo = pathinfo($url);
                $title = $pathInfo['filename'];
            }
        }

        return ['title' => $title, 'image' => $imageUrl, 'image_count' => $imageCount];
    }

    private function handleImageLinks($url)
    {
        // If it's an image, just return it
        $extension = substr(strrchr($url,'.'),1);
        $imageTypes = ['png', 'jpg', 'jpeg', 'gif', 'svg'];

        if (in_array($extension, $imageTypes)) {
            return $url;
        }

        return null;
    }

    private function cleanKnownUrl($url)
    {
        $urlArray = parse_url($url);

        // If the URL is from Imgur, find it's information using the main imgur URL
        if (isset($urlArray['host']) && $urlArray['host'] == 'i.imgur.com') {
            // Adjust the path
            $path = $urlArray['path'];
            $pieces = explode('.', $path);
            if (isset($pieces[0])) {
                return 'http://imgur.com' . $pieces[0];
            }
        }

        return null;
    }
}