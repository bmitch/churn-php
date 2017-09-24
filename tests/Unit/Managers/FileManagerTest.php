<?php declare(strict_types = 1);

namespace Churn\Tests\Results;

use Churn\Configuration\Config;
use Churn\Managers\FileManager;
use Churn\Tests\BaseTestCase;

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
        $this->assertCount(3, $this->fileManager->getPhpFiles([__DIR__ . '/../Assets']));
    }

    /** @test */
    public function it_can_get_the_php_files_in_multiple_directories()
    {
        $this->assertCount(4, $this->fileManager->getPhpFiles([__DIR__ . '/../Assets', __DIR__ . '/../Assets2']));
    }

    /** @test **/
    public function it_ignores_files_specified_to_ignore_in_the_config()
    {
        $fileManager = new FileManager(Config::create(['filesToIgnore' => ['Assets/Baz.php']]));
        $this->assertCount(2, $fileManager->getPhpFiles([__DIR__ . '/../Assets']));
    }

    /** @test **/
    public function it_uses_extensions_specified_in_the_config()
    {
        $fileManager = new FileManager(Config::create(['fileExtensions' => ['php', 'inc']]));
        $this->assertCount(4, $fileManager->getPhpFiles([__DIR__ . '/../Assets']));
    }

    public function setup()
    {
        $this->fileManager = new FileManager(Config::createFromDefaultValues());
    }
}
