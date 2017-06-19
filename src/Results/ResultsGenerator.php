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
