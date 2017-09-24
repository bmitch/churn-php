<?php declare(strict_types = 1);

namespace Churn\Tests\Results;

use Churn\Configuration\Config;
use Churn\Factories\ProcessFactory;
use Churn\Processes\ChurnProcess;
use Churn\Tests\BaseTestCase;
use Churn\Values\File;

class ProcessFactoryTest extends BaseTestCase
{

    private $processFactory;

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(ProcessFactory::class, $this->processFactory);
    }

    /** @test **/
    public function it_can_create_a_git_commit_count_process()
    {
        $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
        $result = $this->processFactory->createGitCommitProcess($file);
        $this->assertInstanceOf(ChurnProcess::class, $result);
        $this->assertSame('GitCommitProcess', $result->getType());
    }

    /** @test **/
    public function it_can_create_a_cyclomatic_complexity_process()
    {
        $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
        $result = $this->processFactory->createCyclomaticComplexityProcess($file);
        $this->assertInstanceOf(ChurnProcess::class, $result);
        $this->assertSame('CyclomaticComplexityProcess', $result->getType());
    }

    public function setup()
    {
        $this->processFactory = new ProcessFactory(Config::createFromDefaultValues());
    }
}