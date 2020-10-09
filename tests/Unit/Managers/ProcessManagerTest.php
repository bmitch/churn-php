<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Managers;

use Churn\Collections\FileCollection;
use Churn\Configuration\Config;
use Churn\Factories\ProcessFactory;
use Churn\Managers\ProcessManager;
use Churn\Tests\BaseTestCase;
use Churn\Values\File;

class ProcessManagerTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated() 
    {
        $this->assertInstanceOf(ProcessManager::class, new ProcessManager);
    }

    /** @test */
    public function it_returns_empty_collection_when_no_files() 
    {
        $numParallelJobs = 3;
        $processManager = new ProcessManager();
        $config = Config::createFromDefaultValues();
        $processFactory = new ProcessFactory($config->getCommitsSince());
        $fileCollection = new FileCollection();
        
        $collection = $processManager->process($fileCollection, $processFactory, $numParallelJobs);
        $this->assertEquals($collection->count(), 0);        
    }

    /** @test */
    public function it_returns_correct_collection_count_when_files_given()
    {
        $numParallelJobs = 3;
        $processManager = new ProcessManager();
        $config = Config::createFromDefaultValues();
        $processFactory = new ProcessFactory($config->getCommitsSince());
        $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
        $file2 = new File(['fullPath' => 'bar/foo/baz.php', 'displayPath' => 'foo/baz.php']);
        $fileCollection = new FileCollection(collect([$file]));
        
        $collection = $processManager->process($fileCollection, $processFactory, $numParallelJobs);
        $this->assertEquals($collection->count(), 0);        
    }
}
