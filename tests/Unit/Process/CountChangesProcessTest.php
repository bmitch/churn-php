<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Process;

use Churn\File\File;
use Churn\Tests\BaseTestCase;
use Churn\Process\CountChangesProcess;
use Mockery as m;
use Symfony\Component\Process\Process;

class CountChangesProcessTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = new Process(['foo']);
        $churnProcess = new CountChangesProcess($file, $process);
        $this->assertInstanceOf(CountChangesProcess::class, $churnProcess);
    }

    /** @test */
    public function it_can_be_started()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $process->shouldReceive('start');
        $churnProcess = new CountChangesProcess($file, $process);
        $churnProcess->start();
    }

    /** @test */
    public function it_can_determine_if_it_was_successful()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $process->shouldReceive('getExitCode')->andReturn(0);
        $churnProcess = new CountChangesProcess($file, $process);
        $this->assertTrue($churnProcess->isSuccessful());

        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $process->shouldReceive('getExitCode')->andReturn(null);
        $churnProcess = new CountChangesProcess($file, $process);
        $this->assertFalse($churnProcess->isSuccessful());
    }

    /** @test */
    public function it_can_get_the_name_of_the_file_it_is_processing()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $churnProcess = new CountChangesProcess($file, $process);
        $this->assertSame('bar/baz.php', $churnProcess->getFilename());
    }

    /** @test */
    public function it_can_get_the_file_it_is_processing()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $churnProcess = new CountChangesProcess($file, $process);
        $this->assertSame($file, $churnProcess->getFile());
    }

    /** @test */
    public function it_can_get_its_key()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $churnProcess = new CountChangesProcess($file, $process);
        $this->assertSame('CountChangesfoo/bar/baz.php', $churnProcess->getKey());
    }

    /** @test */
    public function it_can_get_its_type()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $churnProcess = new CountChangesProcess($file, $process);
        $this->assertSame('CountChanges', $churnProcess->getType());
    }

    /** @test */
    public function it_can_get_the_number_of_changes()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = m::mock(Process::class);
        $process->shouldReceive('getOutput')->andReturn('42');
        $churnProcess = new CountChangesProcess($file, $process);
        $this->assertSame(42, $churnProcess->countChanges());
    }
}
