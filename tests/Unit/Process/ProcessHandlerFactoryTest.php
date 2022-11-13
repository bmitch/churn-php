<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Process;

use Churn\Configuration\Config;
use Churn\Event\Broker;
use Churn\Process\Handler\ParallelProcessHandler;
use Churn\Process\Handler\SequentialProcessHandler;
use Churn\Process\ProcessHandlerFactory;
use Churn\Tests\BaseTestCase;
use Mockery as m;

class ProcessHandlerFactoryTest extends BaseTestCase
{
    /**
     * @test
     * @dataProvider provide_config_with_process_handler
     */
    public function it_returns_the_right_process_handler(Config $config, string $expectedClassName): void
    {
        $broker = m::mock(Broker::class);
        $factory = new ProcessHandlerFactory();
        $processHandler = $factory->getProcessHandler($config, $broker);
        self::assertSame($expectedClassName, get_class($processHandler));
    }

    /**
     * @return iterable<array{Config, string}>
     */
    public function provide_config_with_process_handler(): iterable
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
