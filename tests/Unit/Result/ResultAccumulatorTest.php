<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Result;

use Churn\File\File;
use Churn\Result\ResultInterface;
use Churn\Result\ResultAccumulator;
use Churn\Tests\BaseTestCase;
use Mockery as m;

class ResultAccumulatorTest extends BaseTestCase
{
    private function mockResult(int $commits, int $complexity, string $file, float $score = 0.0): ResultInterface
    {
        $result = m::mock(ResultInterface::class);
        $result->shouldReceive('getCommits')->andReturn($commits);
        $result->shouldReceive('getComplexity')->andReturn($complexity);
        $result->shouldReceive('getPriority')->andReturn($commits * $complexity);
        $result->shouldReceive('getFile')->andReturn(new File("/$file", $file));
        $result->shouldReceive('getScore')->andReturn($score);

        return $result;
    }

    /** @test */
    public function it_returns_max_commits()
    {
        $accumulator = new ResultAccumulator(10, 0.1);
        $accumulator->add($this->mockResult(2, 1, 'file'));
        $accumulator->add($this->mockResult(1, 1, 'file'));
        $accumulator->add($this->mockResult(4, 1, 'file'));
        $accumulator->add($this->mockResult(3, 1, 'file'));
        $this->assertEquals(4, $accumulator->getMaxCommits());
    }

    /** @test */
    public function it_returns_max_complexity(): void
    {
        $accumulator = new ResultAccumulator(10, 0.1);
        $accumulator->add($this->mockResult(1, 2, 'file'));
        $accumulator->add($this->mockResult(1, 1, 'file'));
        $accumulator->add($this->mockResult(1, 4, 'file'));
        $accumulator->add($this->mockResult(1, 3, 'file'));
        $this->assertEquals(4, $accumulator->getMaxComplexity());
    }

    /** @test */
    public function it_returns_the_number_of_files(): void
    {
        $accumulator = new ResultAccumulator(2, 0.1);
        $accumulator->add($this->mockResult(1, 2, 'file'));
        $accumulator->add($this->mockResult(1, 1, 'file'));
        $accumulator->add($this->mockResult(1, 4, 'file'));
        $this->assertEquals(3, $accumulator->getNumberOfFiles());
    }

    /** @test */
    public function it_returns_metrics_as_an_array(): void
    {
        $accumulator = new ResultAccumulator(10, 0.1);
        $accumulator->add($this->mockResult(10, 2, 'file1', 0.3));
        $accumulator->add($this->mockResult(5, 1, 'file2', 0.2));
        $accumulator->add($this->mockResult(1, 4, 'file3', 0.1));

        $expectedResult = [
            ['file1', 10, 2, 0.3],
            ['file2', 5, 1, 0.2],
            ['file3', 1, 4, 0.1],
        ];

        $this->assertEquals($expectedResult, $accumulator->toArray());
    }

    /** @test */
    public function it_takes_min_score_into_account(): void
    {
        $accumulator = new ResultAccumulator(10, 0.1);
        $accumulator->add($this->mockResult(10, 2, 'file1', 0.3));
        $accumulator->add($this->mockResult(5, 1, 'file2', 0.2));
        $accumulator->add($this->mockResult(1, 4, 'file3', 0.1));
        $accumulator->add($this->mockResult(2, 1, 'file4', 0.0));
        $accumulator->add($this->mockResult(1, 1, 'file5', -0.1));

        $expectedResult = [
            ['file1', 10, 2, 0.3],
            ['file2', 5, 1, 0.2],
            ['file3', 1, 4, 0.1],
        ];

        $this->assertEquals($expectedResult, $accumulator->toArray());
    }

    /** @test */
    public function it_ignores_files_with_a_score_of_zero(): void
    {
        $accumulator = new ResultAccumulator(10, 0.1);
        $accumulator->add($this->mockResult(0, 1, 'file1', 0.3));
        $accumulator->add($this->mockResult(1, 0, 'file2', 0.2));
        $accumulator->add($this->mockResult(0, 0, 'file3', 0.1));

        $this->assertEquals(0, $accumulator->getNumberOfFiles());
    }

    /** @test */
    public function it_returns_the_max_score(): void
    {
        $accumulator = new ResultAccumulator(10, 0.1);

        $this->assertNull($accumulator->getMaxScore());

        $accumulator->add($this->mockResult(5, 1, 'file2', 0.2));
        $accumulator->add($this->mockResult(10, 2, 'file1', 0.3));
        $accumulator->add($this->mockResult(1, 4, 'file3', 0.1));

        $this->assertEquals(0.3, $accumulator->getMaxScore());
    }
}
