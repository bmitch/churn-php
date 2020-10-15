<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Processes;

use Churn\Configuration\Config;
use Churn\Processes\Handler\ParallelProcessHandler;
use Churn\Processes\Handler\SequentialProcessHandler;
use Churn\Processes\ProcessHandlerFactory;
use Churn\Tests\BaseTestCase;
use Mockery as m;

class ProcessHandlerFactoryTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(ProcessHandlerFactory::class, new ProcessHandlerFactory());
    }

    /**
     * @test
     * @dataProvider provideConfigWithCorrespondingProcessHandler
     */
    public function it_returns_the_right_process_handler(Config $config, string $expectedClassName)
    {
        $factory = new ProcessHandlerFactory();
        $processHandler = $factory->getProcessHandler($config);
        $this->assertEquals($expectedClassName, get_class($processHandler));
    }

    public function provideConfigWithCorrespondingProcessHandler(): iterable
    {
        $config = m::mock(Config::class);
        $config->shouldReceive('getParallelJobs')->andReturn(0);
        yield [$config, SequentialProcessHandler::class];
        
        $config = m::mock(Config::class);
        $config->shouldReceive('getParallelJobs')->andReturn(1);
        yield [$config, SequentialProcessHandler::class];
        
        $config = m::mock(Config::class);
        $config->shouldReceive('getParallelJobs')->andReturn(2);
        yield [$config, ParallelProcessHandler::class];
    }
}
