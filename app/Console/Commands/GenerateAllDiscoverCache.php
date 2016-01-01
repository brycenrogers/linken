<?php

namespace App\Console\Commands;

use App\Handlers\DiscoverCacheHandler;
use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\SearchHandlerInterface;
use Illuminate\Console\Command;

class GenerateAllDiscoverCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generateAllDiscoverCache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates cache items for the Discover feature';

    /**
     * Execute the console command.
     *
     * @param DiscoverCacheHandler $discoverCacheHandler
     * @param SearchHandlerInterface $searchHandler
     * @param CacheHandlerInterface $cacheHandler
     * @return mixed
     */
    public function handle(
        DiscoverCacheHandler $discoverCacheHandler,
        SearchHandlerInterface $searchHandler,
        CacheHandlerInterface $cacheHandler)
    {
        $discoverCacheHandler->generateAll($searchHandler, $cacheHandler);
    }
}
