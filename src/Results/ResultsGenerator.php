<?php declare(strict_types = 1);

namespace Churn\Results;

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
     * @param GitCommitCountAssessor       $commitCountAssessor Git Commit Count Assessor.
     * @param CyclomaticComplexityAssessor $complexityAssessor  Cyclomatic Complexity Assessor.
     */
    public function __construct(GitCommitCountAssessor $commitCountAssessor, CyclomaticComplexityAssessor $complexityAssessor)
    {
        $this->commitCountAssessor = $commitCountAssessor;
        $this->complexityAssessor = $complexityAssessor;
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
     * @param  array $file FileData.
     * @return Result
     */
    protected function getResultsForFile(array $file): Result
    {
        $commits    = $this->commitCountAssessor->assess($file['fullPath']);
        $complexity = $this->complexityAssessor->assess($file['fullPath']);

        return new Result([
            'file'       => $file['displayPath'],
            'commits'    => $commits,
            'complexity' => $complexity,
        ]);
    }
}
