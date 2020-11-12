<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Process\Handler;

use Churn\Configuration\Config;
use Churn\File\File;
use Churn\Process\CountChangesProcess;
use Churn\Process\CyclomaticComplexityProcess;
use Churn\Process\Handler\ParallelProcessHandler;
use Churn\Process\Observer\OnSuccess;
use Churn\Process\ProcessFactory;
use Churn\Tests\BaseTestCase;
use Generator;
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

        $observer = m::mock(OnSuccess::class);
        $observer->shouldReceive('__invoke')->never();
        
        $processHandler->process($this->getFileGenerator(), $processFactory, $observer);
    }

    /** @test */
    public function it_calls_the_observer_for_one_file()
    {
        $file = new File(__FILE__, __FILE__);

        $process1 = m::mock(CountChangesProcess::class);
        $process1->shouldReceive('start');
        $process1->shouldReceive('isSuccessful')->andReturn(true);
        $process1->shouldReceive('getFileName')->andReturn(__FILE__);
        $process1->shouldReceive('getType')->andReturn('CountChanges');
        $process1->shouldReceive('getKey')->andReturn('CountChanges' . __FILE__);
        $process1->shouldReceive('getFile')->andReturn($file);
        $process1->shouldReceive('countChanges')->andReturn(1);

        $process2 = m::mock(CyclomaticComplexityProcess::class);
        $process2->shouldReceive('start');
        $process2->shouldReceive('isSuccessful')->andReturn(true);
        $process2->shouldReceive('getFileName')->andReturn(__FILE__);
        $process2->shouldReceive('getType')->andReturn('CyclomaticComplexity');
        $process2->shouldReceive('getKey')->andReturn('CyclomaticComplexity' . __FILE__);
        $process2->shouldReceive('getFile')->andReturn($file);
        $process2->shouldReceive('getCyclomaticComplexity')->andReturn(2);

        $processFactory = m::mock(ProcessFactory::class);
        $processFactory->shouldReceive('createCountChangesProcess')->andReturn($process1);
        $processFactory->shouldReceive('createCyclomaticComplexityProcess')->andReturn($process2);

        $observer = m::mock(OnSuccess::class);
        $observer->shouldReceive('__invoke')->once();

        $processHandler = new ParallelProcessHandler(3);
        $processHandler->process($this->getFileGenerator($file), $processFactory, $observer);
    }

    private function getFileGenerator(File ...$files): Generator
    {
        foreach ($files as $file) {
            yield $file;
        }
    }
}
