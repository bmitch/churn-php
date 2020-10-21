<?php declare(strict_types = 1);

namespace Churn\Tests\Result;

use Churn\Result\Result;
use Churn\Result\ResultAccumulator;
use Churn\Tests\BaseTestCase;
use Mockery as m;

class ResultAccumulatorTest extends BaseTestCase
{
    private function mockResult(int $commits, int $complexity, string $file): Result
    {
        $result = m::mock(Result::class);
        $result->shouldReceive('getCommits')->andReturn($commits);
        $result->shouldReceive('getComplexity')->andReturn($complexity);
        $result->shouldReceive('getPriority')->andReturn($commits * $complexity);
        $result->shouldReceive('getFile')->andReturn($file);

        return $result;
    }

    /** @test */
    public function it_returns_max_commits()
    {
        $result1 = $this->mockResult(2, 1, 'file');
        $result2 = $this->mockResult(1, 1, 'file');
        $result3 = $this->mockResult(4, 1, 'file');
        $result4 = $this->mockResult(3, 1, 'file');
        $accumulator = new ResultAccumulator(10, 0.1);
        $accumulator->add($result1);
        $accumulator->add($result2);
        $accumulator->add($result3);
        $accumulator->add($result4);
        $this->assertEquals(4, $accumulator->getMaxCommits());
    }

    public function it_returns_max_complexity(): void
    {
        $result1 = $this->mockResult(1, 2, 'file');
        $result2 = $this->mockResult(1, 1, 'file');
        $result3 = $this->mockResult(1, 4, 'file');
        $result4 = $this->mockResult(1, 3, 'file');
        $accumulator = new ResultAccumulator(10, 0.1);
        $accumulator->add($result1);
        $accumulator->add($result2);
        $accumulator->add($result3);
        $accumulator->add($result4);
        $this->assertEquals(4, $accumulator->getMaxComplexity());
    }

    public function it_returns_the_number_of_files(): void
    {
        $result1 = $this->mockResult(1, 2, 'file');
        $result2 = $this->mockResult(1, 1, 'file');
        $result3 = $this->mockResult(1, 4, 'file');
        $accumulator = new ResultAccumulator(2, 0.1);
        $accumulator->add($result1);
        $accumulator->add($result2);
        $accumulator->add($result3);
        $this->assertEquals(3, $accumulator->getNumberOfFiles());
    }
}
