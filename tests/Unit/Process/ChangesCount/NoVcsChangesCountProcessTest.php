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

    /** @return void */
    protected function setUp()
    {
        parent::setUp();

        $file = new File('/foo', '/foo');
        $this->process = new NoVcsChangesCountProcess($file);
    }

    /** @test */
    public function it_always_counts_one(): void
    {
        self::assertSame(1, $this->process->countChanges());
    }

    /** @test */
    public function it_does_not_change_after_starting(): void
    {
        $process = clone $this->process;
        $process->start();
        self::assertEqualsCanonicalizing($this->process, $process);
    }

    /** @test */
    public function it_is_always_successful(): void
    {
        self::assertTrue($this->process->isSuccessful());
    }

    /** @test */
    public function it_can_return_the_file(): void
    {
        self::assertSame('/foo', $this->process->getFile()->getDisplayPath());
    }
}
