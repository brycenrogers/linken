<?php

namespace App\Interfaces;

use App\Models\User;

/**
 * Interface ImageHandlerInterface
 * @package App\Interfaces
 * @provider App\Providers\ImageHandlerServiceProvider
 */
interface ImageHandlerInterface
{
    public function generateThumbnail($url);
    public function uploadUserPhoto(User $user, $croppedPhotoData);
}