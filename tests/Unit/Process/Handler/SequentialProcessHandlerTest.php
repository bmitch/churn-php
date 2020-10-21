<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Process\Handler;

use Churn\Collections\FileCollection;
use Churn\Process\ChurnProcess;
use Churn\Process\Handler\SequentialProcessHandler;
use Churn\Process\Observer\OnSuccess;
use Churn\Process\ProcessFactory;
use Churn\Tests\BaseTestCase;
use Churn\Values\File;
use Mockery as m;

class SequentialProcessHandlerTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(SequentialProcessHandler::class, new SequentialProcessHandler());
    }

    /** @test */
    public function it_calls_the_observer_for_one_file()
    {
        $process1 = m::mock(ChurnProcess::class);
        $process1->shouldReceive('start');
        $process1->shouldReceive('isSuccessful')->andReturn(true);
        $process1->shouldReceive('getOutput')->andReturn('1');
        
        $process2 = m::mock(ChurnProcess::class);
        $process2->shouldReceive('start');
        $process2->shouldReceive('isSuccessful')->andReturn(true);
        $process2->shouldReceive('getOutput')->andReturn('2');
        
        $fileCollection = new FileCollection([new File(['fullPath' => __FILE__, 'displayPath' => __FILE__])]);
        $processFactory = m::mock(ProcessFactory::class);
        $processFactory->shouldReceive('createGitCommitProcess')->andReturn($process1);
        $processFactory->shouldReceive('createCyclomaticComplexityProcess')->andReturn($process2);

        $observer = m::mock(OnSuccess::class);
        $observer->shouldReceive('__invoke')->once();

        $processHandler = new SequentialProcessHandler();
        $processHandler->process($fileCollection, $processFactory, $observer);
    }
}
