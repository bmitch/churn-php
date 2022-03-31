<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Command\Helper;

use Churn\Command\Helper\MaxScoreChecker;
use Churn\Result\ResultReporter;
use Churn\Tests\BaseTestCase;
use Mockery as m;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MaxScoreCheckerTest extends BaseTestCase
{

    /**
     * @test
     * @dataProvider provide_arguments
     */
    public function it_can_check_the_max_score(
        bool $expectedResult,
        ?float $threshold,
        ?float $maxScore,
        string $format = 'text',
        ?string $output = null 
    ): void {
        $input = m::mock(InputInterface::class);
        $input->shouldReceive('getOption')->with('format')->andReturn($format);
        $input->shouldReceive('getOption')->with('output')->andReturn($output);

        $output = m::mock(OutputInterface::class);
        $output->shouldReceive('writeln');

        $report = m::mock(ResultReporter::class);
        $report->shouldReceive('getMaxScore')->andReturn($maxScore);

        $checker = new MaxScoreChecker($threshold);

        $this->assertEquals(
            $expectedResult,
            $checker->isOverThreshold($input, $output, $report)
        );
    }

    public function provide_arguments(): iterable
    {
        yield [false, null, null];
        yield [false, 1, null];
        yield [false, null, 1];
        yield [false, 1, 0.1];
        yield [true, 0.1, 1];
        yield [true, 0.1, 1, 'text', '/tmp'];
    }
}
