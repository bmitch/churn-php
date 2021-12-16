<?php

declare(strict_types=1);

namespace Churn\Configuration;

/**
 * @internal
 */
class Config
{
    /**
     * @var array<string>
     */
    protected $directoriesToScan = [];

    /**
     * @var integer
     */
    protected $filesToShow = 10;

    /**
     * @var float|null
     */
    protected $minScoreToShow = 0.1;

    /**
     * @var float|null
     */
    protected $maxScoreThreshold = null;

    /**
     * @var integer
     */
    protected $parallelJobs = 10;

    /**
     * @var string
     */
    protected $commitsSince = '10 years ago';

    /**
     * @var array<string>
     */
    protected $filesToIgnore = [];

    /**
     * @var array<string>
     */
    protected $fileExtensions = ['php'];

    /**
     * @var string
     */
    protected $vcs = 'git';

    /**
     * @var string|null
     */
    protected $cachePath = null;

    /**
     * @var array<string>
     */
    protected $hooks = [];

    /**
     * @var string|null
     */
    protected $path;

    /**
     * @var array<int|string>
     */
    protected $unrecognizedKeys = [];

    /**
     * @param string|null $path The path of the configuration file if any.
     */
    public function __construct(?string $path = null)
    {
        $this->path = $path;
    }

    /**
     * @return array<int|string> The unrecognized keys.
     */
    public function getUnrecognizedKeys(): array
    {
        return $this->unrecognizedKeys;
    }

    /**
     * Return the path of the folder containing the configuration file.
     */
    public function getDirPath(): string
    {
        return null === $this->path
            ? \getcwd()
            : \dirname($this->path);
    }

    /**
     * Get the paths of directories to scan.
     *
     * @return array<string>
     */
    public function getDirectoriesToScan(): array
    {
        return $this->directoriesToScan;
    }

    /**
     * @param array<string> $directoriesToScan Paths of directories to scan.
     */
    public function setDirectoriesToScan(array $directoriesToScan): void
    {
        $this->directoriesToScan = $directoriesToScan;
    }

    /**
     * Get the number of files to display in the results table.
     */
    public function getFilesToShow(): int
    {
        return $this->filesToShow;
    }

    /**
     * Get the minimum score a file need to display (ignored if null).
     */
    public function getMinScoreToShow(): ?float
    {
        return $this->minScoreToShow;
    }

    /**
     * Get the maximum score threshold.
     */
    public function getMaxScoreThreshold(): ?float
    {
        return $this->maxScoreThreshold;
    }

    /**
     * Get the number of parallel jobs to use to process the files.
     */
    public function getParallelJobs(): int
    {
        return $this->parallelJobs;
    }

    /**
     * @param integer $parallelJobs Number of parallel jobs.
     */
    public function setParallelJobs(int $parallelJobs): void
    {
        $this->parallelJobs = $parallelJobs;
    }

    /**
     * Get how far back in the history to go to count commits.
     */
    public function getCommitsSince(): string
    {
        return $this->commitsSince;
    }

    /**
     * @return array<string>
     */
    public function getFilesToIgnore(): array
    {
        return $this->filesToIgnore;
    }

    /**
     * Get the file extensions to use when processing.
     *
     * @return array<string>
     */
    public function getFileExtensions(): array
    {
        return $this->fileExtensions;
    }

    /**
     * Get the version control system.
     */
    public function getVCS(): string
    {
        return $this->vcs;
    }

    /**
     * Get the cache file path.
     */
    public function getCachePath(): ?string
    {
        return $this->cachePath;
    }

    /**
     * Get the hooks.
     *
     * @return array<string>
     */
    public function getHooks(): array
    {
        return $this->hooks;
    }
}
