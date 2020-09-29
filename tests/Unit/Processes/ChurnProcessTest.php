<?php

use Mockery as m;
use Churn\Tests\BaseTestCase;
use Churn\Processes\ChurnProcess;
use Churn\Values\File;
use Symfony\Component\Process\Process;

class ChurnProcessTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
        $process = new Process(['foo']);
        $churnProcess = new ChurnProcess($file, $process, 'footype');
        $this->assertInstanceOf(ChurnProcess::class, $churnProcess);
    }

    /** @test */
    public function it_can_be_started()
    {
        $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
        $process = m::mock(Process::class);
        $process->shouldReceive('start');
        $churnProcess = new ChurnProcess($file, $process, 'footype');
        $churnProcess->start();
    }

    /** @test */
    public function it_can_determine_if_it_was_successful()
    {
        $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
        $process = m::mock(Process::class);
        $process->shouldReceive('isSuccessful')->andReturn(true);
        $churnProcess = new ChurnProcess($file, $process, 'footype');
        $this->assertTrue($churnProcess->isSuccessful());

        $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
        $process = m::mock(Process::class);
        $process->shouldReceive('isSuccessful')->andReturn(false);
        $churnProcess = new ChurnProcess($file, $process, 'footype');
        $this->assertFalse($churnProcess->isSuccessful());
    }

   /** @test */
   public function it_can_get_the_name_of_the_file_it_is_processing()
   {
        $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
        $process = m::mock(Process::class);
        $churnProcess = new ChurnProcess($file, $process, 'footype');
        $this->assertSame('bar/baz.php', $churnProcess->getFilename());
   }

    /** @test */
    public function it_can_get_its_key()
    {
        $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
        $process = m::mock(Process::class);
        $churnProcess = new ChurnProcess($file, $process, 'footype');
        $this->assertSame('footypefoo/bar/baz.php', $churnProcess->getKey());
    }

   /** @test */
   public function it_can_get_its_type()
   {
       $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
       $process = m::mock(Process::class);
       $churnProcess = new ChurnProcess($file, $process, 'footype');
       $this->assertSame('footype', $churnProcess->getType());
   }

   /** @test */
   public function it_can_get_its_output()
   {
       $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
       $process = m::mock(Process::class);
       $process->shouldReceive('getOutput')->andReturn('mock output');
       $churnProcess = new ChurnProcess($file, $process, 'footype');
       $this->assertSame('mock output', $churnProcess->getOutput());
   }
}
