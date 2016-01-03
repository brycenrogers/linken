<?php

namespace App\Console\Commands;

use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\ImageHandlerInterface;
use App\Models\Link;
use Illuminate\Console\Command;
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
    public function handle(ImageHandlerInterface $imageHandler, CacheHandlerInterface $cacheHandler)
    {
        // Find all links with photos URLs that need thumbnails
        $links = Link::with('item', 'item.user')
            ->whereNull('photo')
            ->take(100)
            ->get();

        /* @var $link \App\Models\Link */
        foreach ($links as $link) {
            // Try to generate a thumbnail for the desired photo
            try {
                $generatedFilename = $imageHandler->generateThumbnail($link->photo_url);
                if ($generatedFilename) {
                    $link->photo = $generatedFilename;
                    $link->save();
                    SearchIndex::upsertToIndex($link);

                    // Update cache for user
                    $cacheHandler->del(CacheHandlerInterface::MAINPAGE, $link->item->user->id);
                }
            }
            catch (\Exception $e) {
                error_log($e);
            }
        }
    }
}
