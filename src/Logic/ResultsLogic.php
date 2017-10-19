<?php declare(strict_types = 1);

namespace Churn\Logic;

use Churn\Results\ResultsParser;
use Illuminate\Support\Collection;

class ResultsLogic
{
    /**
     * ResultsLogic constructor.
     * @param ResultsParser $parser Results Parser.
     */
    public function __construct(ResultsParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Processes the results into a ResultCollection.
     * @param Collection $completedProcesses Collection of completed processes.
     * @param float      $minScore           Minimum score to show.
     * @param integer    $filesToShow        Max number of files to show.
     * @return mixed
     */
    public function process(Collection $completedProcesses, float $minScore, int $filesToShow)
    {
        $resultCollection = $this->parser->parse($completedProcesses);
        $resultCollection = $resultCollection->whereScoreAbove($minScore);
        $resultCollection = $resultCollection->orderByScoreDesc();
        return $resultCollection->take($filesToShow);
    }
}
