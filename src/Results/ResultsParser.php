<?php declare(strict_types = 1);


namespace Churn\Results;

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
     * @param array  $processes The processes that were executed on the file.
     * @return void
     */
    private function parseCompletedProcessesForFile(string $file, array $processes): void
    {
        $commits = (integer) $processes['GitCommitProcess']->getOutput();
        $complexity = (integer) $processes['CyclomaticComplexityProcess']->getOutput();

        $result = new Result([
            'file' => $file,
            'commits' => $commits,
            'complexity' => $complexity,
        ]);

        $this->resultsCollection->push($result);
    }
}
