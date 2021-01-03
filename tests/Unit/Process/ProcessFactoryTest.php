<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Process;

use Churn\Configuration\Config;
use Churn\File\File;
use Churn\Process\ChangesCountInterface;
use Churn\Process\CyclomaticComplexityInterface;
use Churn\Process\ProcessFactory;
use Churn\Tests\BaseTestCase;
use InvalidArgumentException;

class ProcessFactoryTest extends BaseTestCase
{
    /**
     * @var ProcessFactory
     */
    private $processFactory;

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(ProcessFactory::class, $this->processFactory);
    }

    /** @test */
    public function it_can_create_a_git_commit_count_process()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $result = $this->processFactory->createChangesCountProcess($file);
        $this->assertInstanceOf(ChangesCountInterface::class, $result);
        $this->assertSame($file, $result->getFile());
    }

    /** @test */
    public function it_can_create_a_cyclomatic_complexity_process()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $result = $this->processFactory->createCyclomaticComplexityProcess($file);
        $this->assertInstanceOf(CyclomaticComplexityInterface::class, $result);
        $this->assertSame($file, $result->getFile());
    }

    /** @test */
    public function it_throws_exception_if_VCS_is_not_supported()
    {
        $config = Config::createFromDefaultValues();
        $this->expectException(InvalidArgumentException::class);
        $this->processFactory = new ProcessFactory('not a valid VCS', $config->getCommitsSince());
    }

    /** @test */
    public function it_always_counts_one_when_there_is_no_VCS()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $this->processFactory = new ProcessFactory('none', '');
        $result = $this->processFactory->createChangesCountProcess($file);
        $this->assertSame($file, $result->getFile());
        $this->assertEquals(1, $result->countChanges());
    }

    public function setup()
    {
        $config = Config::createFromDefaultValues();
        $this->processFactory = new ProcessFactory($config->getVCS(), $config->getCommitsSince());
    }
}
