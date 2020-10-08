<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Managers;

use Churn\Configuration\Config;
use Churn\Managers\ProcessManager;
use Churn\Tests\BaseTestCase;
use Churn\Values\File;

use Illuminate\Support\Collection;

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
        $collection = new ProcessManager();
        $collection = $collection->process(new FileCollection, new ProcessFactory, $numParallelJobs);
        $this->assertEquals($collection->count(), $numParallelJobs);        
    }
}

?>
