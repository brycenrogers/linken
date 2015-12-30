<?php

namespace App\Interfaces;

interface LinkParserInterface {
    public function parseLink($url, $imageNumber = null);
}