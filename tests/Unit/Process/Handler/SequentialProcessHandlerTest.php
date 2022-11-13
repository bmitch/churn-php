<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Process\Handler;

use Churn\Event\Broker;
use Churn\File\File;
use Churn\Process\ChangesCountInterface;
use Churn\Process\CyclomaticComplexityInterface;
use Churn\Process\Handler\SequentialProcessHandler;
use Churn\Process\ProcessFactory;
use Churn\Tests\BaseTestCase;
use Generator;
use Mockery as m;

class SequentialProcessHandlerTest extends BaseTestCase
{
    /** @test */
    public function it_calls_the_broker_for_one_file(): void
    {
        $process1 = m::mock(ChangesCountInterface::class);
        $process1->shouldReceive('start');
        $process1->shouldReceive('isSuccessful')->andReturn(true);
        $process1->shouldReceive('countChanges')->andReturn(1);

        $process2 = m::mock(CyclomaticComplexityInterface::class);
        $process2->shouldReceive('start');
        $process2->shouldReceive('isSuccessful')->andReturn(true);
        $process2->shouldReceive('getCyclomaticComplexity')->andReturn(2);

        $processFactory = m::mock(ProcessFactory::class);
        $processFactory->shouldReceive('createProcesses')->andReturn([$process1, $process2]);

        $broker = m::mock(Broker::class);
        $broker->shouldReceive('notify')->once();

        $processHandler = new SequentialProcessHandler($broker);
        $processHandler->process($this->getFileGenerator(), $processFactory);
    }

    private function getFileGenerator(): Generator
    {
        yield new File(__FILE__, __FILE__);
    }
}
