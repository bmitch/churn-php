<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Process;

use Churn\Process\CacheProcessFactory;
use Churn\Process\ProcessFactory;
use Churn\Tests\BaseTestCase;
use InvalidArgumentException;
use Mockery as m;

class CacheProcessFactoryTest extends BaseTestCase
{
    /**
     * @test
     * @dataProvider provide_invalid_paths
     */
    public function it_throws_for_invalid_cache_path(string $cachePath): void
    {
        $factory = m::mock(ProcessFactory::class);
        $this->expectException(InvalidArgumentException::class);
        new CacheProcessFactory($cachePath, $factory);
    }

    public function provide_invalid_paths(): iterable
    {
        yield [''];
        yield [__DIR__];
    }
}
