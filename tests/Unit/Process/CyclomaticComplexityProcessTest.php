<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Process;

use Churn\File\File;
use Churn\Tests\BaseTestCase;
use Churn\Process\CyclomaticComplexityProcess;
use Mockery as m;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CyclomaticComplexityProcessTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_started(): void
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $process->shouldReceive('start')->once();
        $churnProcess = new CyclomaticComplexityProcess($file, $process);
        $churnProcess->start();
    }

    /** @test */
    public function it_can_determine_if_it_was_successful(): void
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $process->shouldReceive('getExitCode')->andReturn(0);
        $churnProcess = new CyclomaticComplexityProcess($file, $process);
        self::assertTrue($churnProcess->isSuccessful());
    }

    /** @test */
    public function it_can_determine_if_it_was_unsuccessful(): void
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $process->shouldReceive('getExitCode')->andReturn(null);
        $churnProcess = new CyclomaticComplexityProcess($file, $process);
        self::assertFalse($churnProcess->isSuccessful());
    }

    /** @test */
    public function it_throws_with_positive_exit_code(): void
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $process->shouldReceive('getExitCode')->andReturn(1);
        $process->shouldIgnoreMissing();
        $churnProcess = new CyclomaticComplexityProcess($file, $process);

        $this->expectException(ProcessFailedException::class);

        $churnProcess->isSuccessful();
    }

    /** @test */
    public function it_can_get_the_file_it_is_processing(): void
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $churnProcess = new CyclomaticComplexityProcess($file, $process);
        self::assertSame($file, $churnProcess->getFile());
    }

    /** @test */
    public function it_can_get_the_cyclomatic_complexity(): void
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $process->shouldReceive('getOutput')->andReturn('123');
        $churnProcess = new CyclomaticComplexityProcess($file, $process);
        self::assertSame(123, $churnProcess->getCyclomaticComplexity());
    }
}
