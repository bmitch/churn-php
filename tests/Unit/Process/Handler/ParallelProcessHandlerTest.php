<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Process\Handler;

use Churn\Event\Broker;
use Churn\File\File;
use Churn\Process\ChangesCountInterface;
use Churn\Process\CyclomaticComplexityProcess;
use Churn\Process\Handler\ParallelProcessHandler;
use Churn\Process\ConcreteProcessFactory;
use Churn\Process\ProcessFactory;
use Churn\Tests\BaseTestCase;
use Generator;
use Mockery as m;

class ParallelProcessHandlerTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $broker = m::mock(Broker::class);
        $this->assertInstanceOf(ParallelProcessHandler::class, new ParallelProcessHandler(2, $broker));
    }

    /** @test */
    public function it_doesnt_call_the_observer_when_no_file()
    {
        $broker = m::mock(Broker::class);
        $processHandler = new ParallelProcessHandler(3, $broker);
        $processFactory = new ConcreteProcessFactory('none', '');

        $observer = m::mock(OnSuccess::class);
        $observer->shouldReceive('__invoke')->never();

        $processHandler->process($this->getFileGenerator(), $processFactory, $observer);
    }

    /** @test */
    public function it_calls_the_broker_for_one_file()
    {
        $file = new File(__FILE__, __FILE__);

        $process1 = m::mock(ChangesCountInterface::class);
        $process1->shouldReceive('start');
        $process1->shouldReceive('isSuccessful')->andReturn(true);
        $process1->shouldReceive('getFile')->andReturn($file);
        $process1->shouldReceive('countChanges')->andReturn(1);

        $process2 = m::mock(CyclomaticComplexityProcess::class);
        $process2->shouldReceive('start');
        $process2->shouldReceive('isSuccessful')->andReturn(true);
        $process2->shouldReceive('getFile')->andReturn($file);
        $process2->shouldReceive('getCyclomaticComplexity')->andReturn(2);

        $processFactory = m::mock(ProcessFactory::class);
        $processFactory->shouldReceive('createProcesses')->andReturn([$process1, $process2]);

        $broker = m::mock(Broker::class);
        $broker->shouldReceive('notify')->once();

        $processHandler = new ParallelProcessHandler(3, $broker);
        $processHandler->process($this->getFileGenerator($file), $processFactory);
    }

    private function getFileGenerator(File ...$files): Generator
    {
        foreach ($files as $file) {
            yield $file;
        }
    }
}
