<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Process\Handler;

use Churn\Collections\FileCollection;
use Churn\Configuration\Config;
use Churn\Process\ChurnProcess;
use Churn\Process\Handler\ParallelProcessHandler;
use Churn\Process\Observer\OnSuccess;
use Churn\Process\ProcessFactory;
use Churn\Tests\BaseTestCase;
use Churn\Values\File;
use Mockery as m;

class ParallelProcessHandlerTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(ParallelProcessHandler::class, new ParallelProcessHandler(2));
    }

    /** @test */
    public function it_doesnt_call_the_observer_when_no_file()
    {
        $processHandler = new ParallelProcessHandler(3);
        $config = Config::createFromDefaultValues();
        $processFactory = new ProcessFactory($config->getCommitsSince());
        $fileCollection = new FileCollection();

        $observer = m::mock(OnSuccess::class);
        $observer->shouldReceive('__invoke')->never();
        
        $processHandler->process($fileCollection, $processFactory, $observer);
    }

    /** @test */
    public function it_calls_the_observer_for_one_file()
    {
        $file = new File(['fullPath' => __FILE__, 'displayPath' => __FILE__]);

        $process1 = m::mock(ChurnProcess::class);
        $process1->shouldReceive('start');
        $process1->shouldReceive('isSuccessful')->andReturn(true);
        $process1->shouldReceive('getFileName')->andReturn(__FILE__);
        $process1->shouldReceive('getType')->andReturn('GitCommitProcess');
        $process1->shouldReceive('getKey')->andReturn('GitCommitProcess' . __FILE__);
        $process1->shouldReceive('getFile')->andReturn($file);
        $process1->shouldReceive('getOutput')->andReturn('1');

        $process2 = m::mock(ChurnProcess::class);
        $process2->shouldReceive('start');
        $process2->shouldReceive('isSuccessful')->andReturn(true);
        $process2->shouldReceive('getFileName')->andReturn(__FILE__);
        $process2->shouldReceive('getType')->andReturn('CyclomaticComplexityProcess');
        $process2->shouldReceive('getKey')->andReturn('CyclomaticComplexityProcess' . __FILE__);
        $process2->shouldReceive('getFile')->andReturn($file);
        $process2->shouldReceive('getOutput')->andReturn('2');

        $fileCollection = new FileCollection([$file]);
        $processFactory = m::mock(ProcessFactory::class);
        $processFactory->shouldReceive('createGitCommitProcess')->andReturn($process1);
        $processFactory->shouldReceive('createCyclomaticComplexityProcess')->andReturn($process2);

        $observer = m::mock(OnSuccess::class);
        $observer->shouldReceive('__invoke')->once();

        $processHandler = new ParallelProcessHandler(3);
        $processHandler->process($fileCollection, $processFactory, $observer);
    }
}
