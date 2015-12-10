<?php

namespace App\Interfaces;

interface ImageHandlerInterface
{
    public function generateThumbnail($url);
}