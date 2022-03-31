<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Process\ChangesCount;

use Churn\File\File;
use Churn\Process\ChangesCount\NoVcsChangesCountProcess;
use Churn\Tests\BaseTestCase;
use Mockery as m;

class NoVcsChangesCountProcessTest extends BaseTestCase
{
    /**
     * @var NoVcsChangesCountProcess
     */
    private $process;

    protected function setUp()
    {
        $file = new File('/foo', '/foo');
        $this->process = new NoVcsChangesCountProcess($file);
    }

    /** @test */
    public function it_always_counts_one()
    {
        $this->assertEquals(1, $this->process->countChanges());
    }

    /** @test */
    public function it_does_not_change_after_starting()
    {
        $process = clone $this->process;
        $process->start();
        $this->assertEquals($this->process, $process);
    }

    /** @test */
    public function it_is_always_successful()
    {
        $this->assertTrue($this->process->isSuccessful());
    }

    /** @test */
    public function it_can_return_the_file()
    {
        $this->assertEquals('/foo', $this->process->getFile()->getDisplayPath());
    }
}
