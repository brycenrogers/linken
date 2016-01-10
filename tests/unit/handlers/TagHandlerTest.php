<?php

namespace Tests\Unit\Handlers;

use TestCase;
use App\Models\Tag;
use App\Handlers\TagHandler;
use Tests\Unit\Fixtures\CacheHandlerFixture;
use Tests\Unit\Fixtures\TagRepositoryFixture;

/**
 * Class TagHandlerTest
 * @package Tests\Unit\Handlers
 */
class TagHandlerTest extends TestCase
{
    /**
     * @var $tagHandler TagHandler
     */
    private $tagHandler;

    /**
     * @var $cacheHandler CacheHandlerFixture
     */
    private $cacheHandler;

    /**
     * @var $tagRepo TagRepositoryFixture
     */
    private $tagRepo;

    public function setUp()
    {
        parent::setUp();
        $this->tagHandler = new TagHandler();

        // Load Dependency Fixtures
        $this->cacheHandler = new CacheHandlerFixture();
        $this->tagRepo = new TagRepositoryFixture();
    }

    public function testTagsForUser_Cached()
    {
        // Test for tags found in cache, should return array of tag names
        $tags = ['han', 'luke', 'vader'];
        $this->cacheHandler->get = $tags;
        $this->assertEquals($tags, $this->tagHandler->getTagsForUser($this->cacheHandler, $this->tagRepo));
    }

    public function testTagsForUser_Uncached()
    {
        // Test for no tags found in cache, should return array of tag names
        $this->cacheHandler->get = false;

        $tagLeia = new Tag();
        $tagLeia->name = 'leia';
        $tagKenobi = new Tag();
        $tagKenobi->name = 'kenobi';

        $tagNames = ['leia', 'kenobi'];
        $this->tagRepo->all = [$tagLeia, $tagKenobi];
        $this->assertEquals($tagNames, $this->tagHandler->getTagsForUser($this->cacheHandler, $this->tagRepo));
    }
}
