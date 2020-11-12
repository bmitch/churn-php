<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Process\Handler;

use Churn\File\File;
use Churn\Process\ChangesCountInterface;
use Churn\Process\CyclomaticComplexityInterface;
use Churn\Process\Handler\SequentialProcessHandler;
use Churn\Process\Observer\OnSuccess;
use Churn\Process\ProcessFactory;
use Churn\Tests\BaseTestCase;
use Generator;
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
        $process1 = m::mock(ChangesCountInterface::class);
        $process1->shouldReceive('start');
        $process1->shouldReceive('isSuccessful')->andReturn(true);
        $process1->shouldReceive('countChanges')->andReturn(1);
        
        $process2 = m::mock(CyclomaticComplexityInterface::class);
        $process2->shouldReceive('start');
        $process2->shouldReceive('isSuccessful')->andReturn(true);
        $process2->shouldReceive('getCyclomaticComplexity')->andReturn(2);
        
        $processFactory = m::mock(ProcessFactory::class);
        $processFactory->shouldReceive('createChangesCountProcess')->andReturn($process1);
        $processFactory->shouldReceive('createCyclomaticComplexityProcess')->andReturn($process2);

        $observer = m::mock(OnSuccess::class);
        $observer->shouldReceive('__invoke')->once();

        $processHandler = new SequentialProcessHandler();
        $processHandler->process($this->getFileGenerator(), $processFactory, $observer);
    }

    private function getFileGenerator(): Generator
    {
        yield new File(__FILE__, __FILE__);
    }
}
