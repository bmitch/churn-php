<?php


namespace Churn\Tests\Results;

use Churn\Processes\ChurnProcess;
use Mockery as m;
use Churn\Results\ResultsParser;
use Churn\Tests\BaseTestCase;
use Churn\Values\File;
use Symfony\Component\Process\Process;
use Tightenco\Collect\Support\Collection;

class ResultsParserTest extends BaseTestCase
{
    /** @test **/
    public function it_can_be_instantiated()
    {
       $this->assertInstanceOf(ResultsParser::class, new ResultsParser());
    }

    /** @test **/
    public function it_can_parse_a_collection_of_completed_processses()
    {
        $completedProcesses = [];
        $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
        $process = m::mock(Process::class);
        $process->shouldReceive('getFileName')->andReturn('foo/bar/baz.php');
        $process->shouldReceive('getType')->andReturn('GitCommitProcess');
        $process->shouldReceive('getOutput')->andReturn("
        3 foo/bar/baz.php\n
        2 \n");
        $churnProcess = new ChurnProcess($file, $process, 'GitCommitProcess');
        $completedProcesses[$process->getFileName()][$process->getType()] = $churnProcess;

        $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
        $process = m::mock(Process::class);
        $process->shouldReceive('getFileName')->andReturn('foo/bar/baz.php');
        $process->shouldReceive('getType')->andReturn('CyclomaticComplexityProcess');
        $process->shouldReceive('getOutput')->andReturn('4');
        $churnProcess = new ChurnProcess($file, $process, 'CyclomaticComplexityProcess');
        $completedProcesses[$process->getFileName()][$process->getType()] = $churnProcess;

        $resultsParser = new ResultsParser;
        $parsedResults = $resultsParser->parse(new Collection($completedProcesses));

        $this->assertCount(1, $parsedResults);
        $this->assertSame('foo/bar/baz.php', $parsedResults[0]->getFile());
        $this->assertSame(3, $parsedResults[0]->getCommits());
        $this->assertSame(['foo/bar/baz.php', 3, 4], $parsedResults[0]->toArray());
    }

    /** @test **/
    public function it_can_parse_a_completed_process_of_a_file_with_no_git_log()
    {
        $completedProcesses = [];
        $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
        $process = m::mock(Process::class);
        $process->shouldReceive('getFileName')->andReturn('foo/bar/baz.php');
        $process->shouldReceive('getType')->andReturn('GitCommitProcess');
        $process->shouldReceive('getOutput')->andReturn('');
        $churnProcess = new ChurnProcess($file, $process, 'GitCommitProcess');
        $completedProcesses[$process->getFileName()][$process->getType()] = $churnProcess;

        $file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
        $process = m::mock(Process::class);
        $process->shouldReceive('getFileName')->andReturn('foo/bar/baz.php');
        $process->shouldReceive('getType')->andReturn('CyclomaticComplexityProcess');
        $process->shouldReceive('getOutput')->andReturn('4');
        $churnProcess = new ChurnProcess($file, $process, 'CyclomaticComplexityProcess');
        $completedProcesses[$process->getFileName()][$process->getType()] = $churnProcess;

        $resultsParser = new ResultsParser;
        $parsedResults = $resultsParser->parse(new Collection($completedProcesses));

        $this->assertCount(1, $parsedResults);
        $this->assertSame('foo/bar/baz.php', $parsedResults[0]->getFile());
        $this->assertSame(0, $parsedResults[0]->getCommits());
        $this->assertSame(['foo/bar/baz.php', 0, 4], $parsedResults[0]->toArray());
    }
}
