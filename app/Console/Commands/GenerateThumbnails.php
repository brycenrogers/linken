<?php

namespace App\Console\Commands;

use App\Interfaces\ImageHandlerInterface;
use App\Models\Link,
    Illuminate\Console\Command;
use SearchIndex;

class GenerateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generateThumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates thumbnails for Links added by users';

    /**
     * Execute the console command.
     *
     * @param ImageHandlerInterface $imageHandler
     * @return mixed
     */
    public function handle(ImageHandlerInterface $imageHandler)
    {
        // Find all links with photos URLs that need thumbnails
        $links = Link::whereNotNull('photo_url')->get();

        /* @var $link \App\Models\Link */
        foreach ($links as $link) {
            // Try to generate a thumbnail for the desired photo
            try {
                $generatedFilename = $imageHandler->generateThumbnail($link->photo_url);
                if ($generatedFilename) {
                    $link->photo = $generatedFilename;
                    $link->photo_url = null;
                    $link->save();
                    SearchIndex::upsertToIndex($link);
                }
            }
            catch (\Exception $e) {
                error_log($e);
            }
        }
    }
}
