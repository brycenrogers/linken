<?php

namespace App\Libraries;

use Imagine\Imagick\Imagine;
use Imagine\Image\Point;
use App\Interfaces\ImageHandlerInterface;

class ImageHandler implements ImageHandlerInterface {

    public function generateThumbnail($url)
    {
        if ( ! $url) {
            throw new \Exception("No url specified");
        }

        $imagine = new Imagine();

        // Open the image
        $image = $imagine->open($url);

        // Create Box from image
        $originalBox = $image->getSize();
        $thumbBox = $originalBox->widen(100);

        // Generate unique name
        $filename = uniqid() . '.png';

        // Resize and Save
        $image->resize($thumbBox)->save(public_path('assets/images/thumbs/' . $filename));

        // Return the filename for reference purposes
        return $filename;
    }

}