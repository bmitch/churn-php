<?php declare(strict_types = 1);

namespace Churn\Tests\Results;

use Churn\Tests\BaseTestCase;
use Churn\Managers\FileManager;

class FileManagerTest extends BaseTestCase
{
    /**
     * The object we're testing.
     * @var FileManager
     */
    protected $fileManager;

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(FileManager::class, $this->fileManager);
    }

    /** @test */
    public function it_can_get_the_php_files_in_a_filter()
    {
        $this->assertCount(3, $this->fileManager->getPhpFiles(__DIR__ . '/../Assets'));
    }

    public function setup()
    {
        $this->fileManager = new FileManager();
    }
}