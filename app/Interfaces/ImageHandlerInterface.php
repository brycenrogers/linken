<?php

namespace App\Interfaces;

/**
 * Interface ImageHandlerInterface
 * @package App\Interfaces
 * @provider App\Providers\ImageHandlerServiceProvider
 */
interface ImageHandlerInterface
{
    public function generateThumbnail($url);
}