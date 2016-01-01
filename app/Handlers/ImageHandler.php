<?php

namespace App\Handlers;

use App\Models\User;
use Auth;
use Imagine\Imagick\Imagine;
use Imagine\Image\Point;
use App\Interfaces\ImageHandlerInterface;

class ImageHandler implements ImageHandlerInterface
{

    public function generateThumbnail($url)
    {
        if (!$url) {
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

    /**
     * Save the cropped photo data to the filesystem and update the User's photo in storage
     *
     * @param $user User
     * @param $croppedPhotoData string Photo bit data
     * @return array
     */
    public function uploadUserPhoto(User $user, $croppedPhotoData)
    {
        $data = file_get_contents($croppedPhotoData);

        if ($data) {
            $filename = $user->id . ".png";
            $destinationPath = public_path() .'/assets/images/uploads/' . $filename;
            if(file_put_contents($destinationPath, $data)) {
                $user = Auth::user();
                $user->user_photo = $filename;
                $user->save();
                return ['flash' => 'success', 'message' => 'Photo updated!'];
            }
        }
        return ['flash' => 'error', 'message' => 'Photo could not be updated :('];
    }

}