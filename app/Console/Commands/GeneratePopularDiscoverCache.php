<?php

namespace App\Console\Commands;

use App\Handlers\DiscoverCacheHandler;
use App\Interfaces\CacheHandlerInterface;
use App\Interfaces\SearchHandlerInterface;
use Illuminate\Console\Command;

class GeneratePopularDiscoverCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generatePopularDiscoverCache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates popular cache items for the Discover feature';

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
        $discoverCacheHandler->generatePopular($searchHandler, $cacheHandler);
    }
}