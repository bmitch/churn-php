<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Process\Handler;

use Churn\Collections\FileCollection;
use Churn\Configuration\Config;
use Churn\Process\ChurnProcess;
use Churn\Process\Handler\ParallelProcessHandler;
use Churn\Process\Observer\OnSuccess;
use Churn\Process\Observer\OnSuccessNull;
use Churn\Process\ProcessFactory;
use Churn\Tests\BaseTestCase;
use Churn\Values\File;
use Illuminate\Support\Collection;
use Mockery as m;

class ParallelProcessHandlerTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(ParallelProcessHandler::class, new ParallelProcessHandler(2));
    }

    /** @test */
    public function it_returns_empty_collection_when_no_files()
    {
        $processHandler = new ParallelProcessHandler(3);
        $config = Config::createFromDefaultValues();
        $processFactory = new ProcessFactory($config->getCommitsSince());
        $fileCollection = new FileCollection();
        
        $collection = $processHandler->process($fileCollection, $processFactory, new OnSuccessNull());
        $this->assertEquals($collection->count(), 0);
    }

    /** @test */
    public function it_returns_a_collection_of_results()
    {
        $file = new File(['fullPath' => __FILE__, 'displayPath' => __FILE__]);

        $process1 = m::mock(ChurnProcess::class);
        $process1->shouldReceive('start');
        $process1->shouldReceive('isSuccessful')->andReturn(true);
        $process1->shouldReceive('getFileName')->andReturn(__FILE__);
        $process1->shouldReceive('getType')->andReturn('GitCommitProcess');
        $process1->shouldReceive('getKey')->andReturn('GitCommitProcess' . __FILE__);
        $process1->shouldReceive('getFile')->andReturn($file);

        $process2 = m::mock(ChurnProcess::class);
        $process2->shouldReceive('start');
        $process2->shouldReceive('isSuccessful')->andReturn(true);
        $process2->shouldReceive('getFileName')->andReturn(__FILE__);
        $process2->shouldReceive('getType')->andReturn('CyclomaticComplexityProcess');
        $process2->shouldReceive('getKey')->andReturn('CyclomaticComplexityProcess' . __FILE__);
        $process2->shouldReceive('getFile')->andReturn($file);

        $fileCollection = new FileCollection([$file]);
        $processFactory = m::mock(ProcessFactory::class);
        $processFactory->shouldReceive('createGitCommitProcess')->andReturn($process1);
        $processFactory->shouldReceive('createCyclomaticComplexityProcess')->andReturn($process2);

        $observer = m::mock(OnSuccess::class);
        $observer->shouldReceive('__invoke')->once();

        $processHandler = new ParallelProcessHandler(3);
        $results = $processHandler->process($fileCollection, $processFactory, $observer);

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(1, $results);
    }
}
