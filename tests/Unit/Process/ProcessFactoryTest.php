<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Process;

use Churn\Configuration\Config;
use Churn\File\File;
use Churn\Process\ChangesCountInterface;
use Churn\Process\CyclomaticComplexityInterface;
use Churn\Process\ProcessFactory;
use Churn\Tests\BaseTestCase;

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

    public function setup()
    {
        $config = Config::createFromDefaultValues();
        $this->processFactory = new ProcessFactory('git', $config->getCommitsSince());
    }
}
