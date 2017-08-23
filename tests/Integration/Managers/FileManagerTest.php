<?php

namespace Churn\Tests\Integration\Managers;

use Churn\Managers\FileManager;
use Churn\Tests\BaseTestCase;
use Churn\Values\Config;
use Churn\Values\File;
use Illuminate\Support\Collection;

class FileManagerTest extends BaseTestCase
{
    /**
     * The class being tested.
     * @var FileManager
     */
    private $fileManager;

    /** @test **/
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(FileManager::class, $this->fileManager);
    }

    /** @test **/
    public function it_can_recursively_get_the_php_files_in_a_path()
    {
        $path = __DIR__ . '';
        $results = $this->fileManager->getPhpFiles($path);
        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(1, $results);
        $this->assertInstanceOf(File::class, $results[0]);
    }

    public function setUp()
    {
        parent::setup();

        $this->fileManager = new FileManager(new Config);
    }
}