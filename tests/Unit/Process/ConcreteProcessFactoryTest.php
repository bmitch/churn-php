<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Process;

use Churn\Configuration\Config;
use Churn\File\File;
use Churn\Process\ChangesCountInterface;
use Churn\Process\CyclomaticComplexityInterface;
use Churn\Process\ConcreteProcessFactory;
use Churn\Tests\BaseTestCase;
use InvalidArgumentException;

class ConcreteProcessFactoryTest extends BaseTestCase
{
    /**
     * @var ConcreteProcessFactory
     */
    private $processFactory;

    public function setup()
    {
        $config = new Config();
        $this->processFactory = new ConcreteProcessFactory($config->getVCS(), $config->getCommitsSince());
    }

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(ConcreteProcessFactory::class, $this->processFactory);
    }

    private function extractChangesCountProcess(iterable $processes): ?ChangesCountInterface
    {
        foreach ($processes as $process) {
            if ($process instanceof ChangesCountInterface) {
                return $process;
            }
        }

        return null;
    }

    private function extractCyclomaticComplexityProcess(iterable $processes): ?CyclomaticComplexityInterface
    {
        foreach ($processes as $process) {
            if ($process instanceof CyclomaticComplexityInterface) {
                return $process;
            }
        }

        return null;
    }

    /** @test */
    public function it_can_create_a_git_commit_count_process()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = $this->extractChangesCountProcess($this->processFactory->createProcesses($file));
        $this->assertInstanceOf(ChangesCountInterface::class, $process);
        $this->assertSame($file, $process->getFile());
    }

    /** @test */
    public function it_can_create_a_cyclomatic_complexity_process()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $process = $this->extractCyclomaticComplexityProcess($this->processFactory->createProcesses($file));
        $this->assertInstanceOf(CyclomaticComplexityInterface::class, $process);
        $this->assertSame($file, $process->getFile());
    }

    /** @test */
    public function it_throws_exception_if_VCS_is_not_supported()
    {
        $config = new Config();
        $this->expectException(InvalidArgumentException::class);
        $this->processFactory = new ConcreteProcessFactory('not a valid VCS', $config->getCommitsSince());
    }

    /** @test */
    public function it_always_counts_one_when_there_is_no_VCS()
    {
        $file = new File('foo/bar/baz.php', 'bar/baz.php');
        $this->processFactory = new ConcreteProcessFactory('none', '');
        $process = $this->extractChangesCountProcess($this->processFactory->createProcesses($file));
        $this->assertSame($file, $process->getFile());
        $this->assertEquals(1, $process->countChanges());
    }
}
