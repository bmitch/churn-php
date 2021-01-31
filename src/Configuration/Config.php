<?php

declare(strict_types=1);

namespace Churn\Configuration;

class Config
{

    private const DIRECTORIES_TO_SCAN = [];
    private const FILES_TO_SHOW = 10;
    private const MINIMUM_SCORE_TO_SHOW = 0.1;
    private const AMOUNT_OF_PARALLEL_JOBS = 10;
    private const SHOW_COMMITS_SINCE = '10 years ago';
    private const FILES_TO_IGNORE = [];
    private const FILE_EXTENSIONS_TO_PARSE = ['php'];
    private const VCS = 'git';

    /**
     * @var array<string, mixed>
     */
    private $configuration;

    /**
     * @param array<mixed> $configuration Raw config data.
     */
    private function __construct(array $configuration = [])
    {
        if (!empty($configuration)) {
            (new Validator())->validateConfigurationValues($configuration);
        }

        $this->configuration = $configuration;
    }

    /**
     * Create a config with given configuration.
     *
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    public static function create(array $configuration): Config
    {
        return new self($configuration);
    }

    /**
     * Create a config with default configuration.
     */
    public static function createFromDefaultValues(): Config
    {
        return new self();
    }

    /**
     * Get the paths of directories to scan.
     *
     * @return array<string>
     */
    public function getDirectoriesToScan(): array
    {
        return $this->configuration['directoriesToScan'] ?? self::DIRECTORIES_TO_SCAN;
    }

    /**
     * @param array<string> $directories Paths of directories to scan.
     */
    public function setDirectoriesToScan(array $directories): void
    {
        $this->configuration['directoriesToScan'] = $directories;
    }

    /**
     * Get the number of files to display in the results table.
     */
    public function getFilesToShow(): int
    {
        return $this->configuration['filesToShow'] ?? self::FILES_TO_SHOW;
    }

    /**
     * Get the minimum score a file need to display.
     */
    public function getMinScoreToShow(): float
    {
        return $this->configuration['minScoreToShow'] ?? self::MINIMUM_SCORE_TO_SHOW;
    }

    /**
     * Get the number of parallel jobs to use to process the files.
     */
    public function getParallelJobs(): int
    {
        return $this->configuration['parallelJobs'] ?? self::AMOUNT_OF_PARALLEL_JOBS;
    }

    /**
     * @param integer $parallelJobs Number of parallel jobs.
     */
    public function setParallelJobs(int $parallelJobs): void
    {
        $this->configuration['parallelJobs'] = $parallelJobs;
    }

    /**
     * Get how far back in the git history to go to count commits.
     */
    public function getCommitsSince(): string
    {
        return $this->configuration['commitsSince'] ?? self::SHOW_COMMITS_SINCE;
    }

    /**
     * @return array<string>
     */
    public function getFilesToIgnore(): array
    {
        return $this->configuration['filesToIgnore'] ?? self::FILES_TO_IGNORE;
    }

    /**
     * Get the file extensions to use when processing.
     *
     * @return array<string>
     */
    public function getFileExtensions(): array
    {
        return $this->configuration['fileExtensions'] ?? self::FILE_EXTENSIONS_TO_PARSE;
    }

    /**
     * Get the version control system.
     */
    public function getVCS(): string
    {
        return $this->configuration['vcs'] ?? self::VCS;
    }
}
