<?php declare(strict_types = 1);

namespace Churn\Results;

use Churn\Services\CommandService;
use Churn\Assessors\GitCommitCount\GitCommitCountAssessor;
use Churn\Assessors\CyclomaticComplexity\CyclomaticComplexityAssessor;

class ResultsGenerator
{

    /**
     * The commit count assesor.
     * @var GitCommitCountAssessor
     */
    protected $commitCountAssessor;

    /**
     * The complexity assesor.
     * @var CyclomaticComplexityAssessor
     */
    protected $complexityAssessor;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->commitCountAssessor = new GitCommitCountAssessor(new CommandService);
        $this->complexityAssessor = new CyclomaticComplexityAssessor();
    }

    /**
     * Generates a ResultCollection for the provided $fils.
     * @param  array $files List of files.
     * @return ResultCollection
     */
    public function getResults(array $files): ResultCollection
    {
        $results = new ResultCollection;
        foreach ($files as $file) {
            $results->push($this->getResultsForFile($file));
        }
        return $results;
    }

    /**
     * Returns a Result object for the provided $file.
     * @param  string $file File.
     * @return Result
     */
    protected function getResultsForFile(string $file): Result
    {
        $commits    = $this->commitCountAssessor->assess($file);
        $complexity = $this->complexityAssessor->assess($file);

        return new Result([
            'file'       => $file,
            'commits'    => $commits,
            'complexity' => $complexity,
        ]);
    }
}
