<?php declare(strict_types = 1);

namespace Churn\Values;

class Config
{
    /**
     * The number of files to display in the results table.
     * @var integer
     */
    private $filesToShow;

    /**
     * The minimum score a file need to display in the results table.
     * @var integer
     */
    private $minScoreToShow;

    /**
     * The number of parallel jobs to use to process the files.
     * @var integer
     */
    private $parallelJobs;

    /**
     * How far back in the git history to go to count commits.
     * @var string
     */
    private $commitsSince;

    /**
     * The paths to files to ignore when processing.
     * @var array
     */
    private $filesToIgnore;

    /**
     * Config constructor.
     * @param array $rawData Raw config data.
     */
    public function __construct(array $rawData = [])
    {
        $this->filesToShow = $rawData['filesToShow'] ?? 10;
        $this->minScoreToShow = $rawData['minScoreToShow'] ?? 0;
        $this->parallelJobs = $rawData['parallelJobs'] ?? 10;
        $this->commitsSince = $rawData['commitsSince'] ?? '10 years ago';
        $this->filesToIgnore = $rawData['filesToIgnore'] ?? [];
    }

    /**
     * Get the number of files to display in the results table.
     * @return integer
     */
    public function getFilesToShow(): int
    {
        return $this->filesToShow;
    }


    /**
     * Get the minimum score a file need to display.
     * @return integer
     */
    public function getMinScoreToShow(): int
    {
        return $this->minScoreToShow;
    }

    /**
     * Get the number of parallel jobs to use to process the files.
     * @return integer
     */
    public function getParallelJobs(): int
    {
        return $this->parallelJobs;
    }

    /**
     * Get how far back in the git history to go to count commits.
     * @return string
     */
    public function getCommitsSince(): string
    {
        return $this->commitsSince;
    }

    /**
     * Get the paths to files to ignore when processing.
     * @return array
     */
    public function getFilesToIgnore(): array
    {
        return $this->filesToIgnore;
    }
}
