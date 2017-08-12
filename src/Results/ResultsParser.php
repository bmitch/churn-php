<?php declare(strict_types = 1);


namespace Churn\Results;

use Churn\Processes\GitCommitCountProcess;
use Illuminate\Support\Collection;

class ResultsParser
{
    /**
     * Collection of results.
     * @var ResultCollection
     */
    private $resultsCollection;

    /**
     * Turns a collection of completed processes into a
     * collection of parsed result objects.
     * @param Collection $completedProcesses Collection of completed processes.
     * @return ResultCollection
     */
    public function parse(Collection $completedProcesses): ResultCollection
    {
        $this->resultsCollection = new ResultCollection;

        foreach ($completedProcesses as $file => $processes) {
            $this->parseCompletedProcessesForFile($file, $processes);
        }

        return $this->resultsCollection;
    }

    /**
     * Parse the list of processes for a file.
     * @param string $file      The file the processes were executed on.
     * @param array  $processes The proceses that were executed on the file.
     * @return void
     */
    private function parseCompletedProcessesForFile(string $file, array $processes)
    {
        $commits = (integer) $this->parseCommits($processes['GitCommitCountProcess']);
        $complexity = (integer) $processes['CyclomaticComplexityProcess']->getOutput();

        $result = new Result([
            'file' => $file,
            'commits' => $commits,
            'complexity' => $complexity,
        ]);

        $this->resultsCollection->push($result);
    }

    /**
     * Parse the number of commits on the file from the raw process output.
     * @param GitCommitCountProcess $process Git Commit Count Process.
     * @return integer
     */
    private function parseCommits(GitCommitCountProcess $process): int
    {
        $output = $process->getOutput();
        preg_match("/([0-9]{1,})/", $output, $matches);
        return (integer) $matches[1] ?? 0;
    }
}
