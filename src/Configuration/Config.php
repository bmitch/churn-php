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
    private $directoriesToScan = [];

    /**
     * @var integer
     */
    private $filesToShow = 10;

    /**
     * @var float|null
     */
    private $minScoreToShow = 0.1;

    /**
     * @var float|null
     */
    private $maxScoreThreshold = null;

    /**
     * @var integer
     */
    private $parallelJobs = 10;

    /**
     * @var string
     */
    private $commitsSince = '10 years ago';

    /**
     * @var array<string>
     */
    private $filesToIgnore = [];

    /**
     * @var array<string>
     */
    private $fileExtensions = ['php'];

    /**
     * @var string
     */
    private $vcs = 'git';

    /**
     * @var string|null
     */
    private $cachePath = null;

    /**
     * @var array<string>
     */
    private $hooks = [];

    /**
     * @var string|null
     */
    private $path;

    /**
     * @var array<int|string>
     */
    private $unrecognizedKeys = [];

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
     * @param array<int|string> $unrecognizedKeys The unrecognized keys.
     */
    public function setUnrecognizedKeys(array $unrecognizedKeys): void
    {
        $this->unrecognizedKeys = $unrecognizedKeys;
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
     * @param integer $filesToShow The number of files to display in the results table.
     */
    public function setFilesToShow(int $filesToShow): void
    {
        $this->filesToShow = $filesToShow;
    }

    /**
     * Get the minimum score a file need to display (ignored if null).
     */
    public function getMinScoreToShow(): ?float
    {
        return $this->minScoreToShow;
    }

    /**
     * @param float|null $minScoreToShow The minimum score for a file to be displayed (ignored if null).
     */
    public function setMinScoreToShow(?float $minScoreToShow): void
    {
        $this->minScoreToShow = $minScoreToShow;
    }

    /**
     * Get the maximum score threshold.
     */
    public function getMaxScoreThreshold(): ?float
    {
        return $this->maxScoreThreshold;
    }

    /**
     * @param float|null $maxScoreThreshold The maximum score threshold.
     */
    public function setMaxScoreThreshold(?float $maxScoreThreshold): void
    {
        $this->maxScoreThreshold = $maxScoreThreshold;
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
     * @param string $commitsSince Criteria to apply when counting changes.
     */
    public function setCommitsSince(string $commitsSince): void
    {
        $this->commitsSince = $commitsSince;
    }

    /**
     * @return array<string>
     */
    public function getFilesToIgnore(): array
    {
        return $this->filesToIgnore;
    }

    /**
     * @param array<string> $filesToIgnore The files to ignore.
     */
    public function setFilesToIgnore(array $filesToIgnore): void
    {
        $this->filesToIgnore = $filesToIgnore;
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
     * @param array<string> $fileExtensions The file extensions to use when processing.
     */
    public function setFileExtensions(array $fileExtensions): void
    {
        $this->fileExtensions = $fileExtensions;
    }

    /**
     * Get the version control system.
     */
    public function getVCS(): string
    {
        return $this->vcs;
    }

    /**
     * @param string $vcs The version control system.
     */
    public function setVCS(string $vcs): void
    {
        $this->vcs = $vcs;
    }

    /**
     * Get the cache file path.
     */
    public function getCachePath(): ?string
    {
        return $this->cachePath;
    }

    /**
     * @param string|null $cachePath The cache file path.
     */
    public function setCachePath(?string $cachePath): void
    {
        $this->cachePath = $cachePath;
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

    /**
     * @param array<string> $hooks The hooks.
     */
    public function setHooks(array $hooks): void
    {
        $this->hooks = $hooks;
    }
}
