<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Managers;

use Churn\Collections\FileCollection;
use Churn\Managers\ProcessManager;
use Churn\Factories\ProcessFactory;
use Churn\Tests\BaseTestCase;
use Churn\Configuration\Config;

class ProcessManagerTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated() 
    {
        $this->assertInstanceOf(ProcessManager::class, new ProcessManager);
    }

    /** @test */
    public function it_returns_collection_with_same_count_as_number_of_parallel_jobs() 
    {
        $numParallelJobs = 3;
        $processManager = new ProcessManager();
        $config = Config::createFromDefaultValues();
        $processFactory = new ProcessFactory($config->getCommitsSince());
        $collection = $processManager->process(new FileCollection, $processFactory, $numParallelJobs);
        $this->assertEquals($collection->count(), $numParallelJobs);        
    }
}
