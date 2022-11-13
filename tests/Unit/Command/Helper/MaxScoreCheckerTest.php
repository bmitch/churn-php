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
        ?float $maxScore
    ): void {
        $input = m::mock(InputInterface::class);
        $input->shouldReceive('getOption')->with('format')->andReturn('text');

        $output = m::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->with('<error>Max score is over the threshold</>')
            ->times((int) $expectedResult)
        ;

        $report = m::mock(ResultReporter::class);
        $report->shouldReceive('getMaxScore')->andReturn($maxScore);

        $checker = new MaxScoreChecker($threshold);

        self::assertSame(
            $expectedResult,
            $checker->isOverThreshold($input, $output, $report)
        );
    }

    /**
     * @return iterable<array{bool, ?float, ?float}>
     */
    public function provide_arguments(): iterable
    {
        yield 'threshold and score are null' => [false, null, null];
        yield 'score is null' => [false, 1, null];
        yield 'threshold is null' => [false, null, 1];
        yield 'score < threshold' => [false, 1, 0.1];
        yield 'score = threshold' => [false, 0.5, 0.5];
        yield 'score > threshold' => [true, 0.1, 1];
    }

    /**
     * @test
     * @dataProvider provide_format_and_output
     */
    public function it_prints_an_error_message(
        string $format,
        ?string $outputPath
    ): void {
        $input = m::mock(InputInterface::class);
        $input->shouldReceive('getOption')->with('format')->andReturn($format);
        $input->shouldReceive('getOption')->with('output')->andReturn($outputPath);

        $output = m::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->with('<error>Max score is over the threshold</>')
            ->once()
        ;

        $report = m::mock(ResultReporter::class);
        $report->shouldReceive('getMaxScore')->andReturn(1);

        $checker = new MaxScoreChecker(0);

        self::assertTrue($checker->isOverThreshold($input, $output, $report));
    }

    /**
     * @return iterable<array{string, ?string}>
     */
    public function provide_format_and_output(): iterable
    {
        yield 'format=text, output is null' => ['text', null];
        yield 'format=text, output is not null' => ['text', '/tmp'];
        yield 'format=json, output is not null' => ['json', '/tmp'];
    }
}
