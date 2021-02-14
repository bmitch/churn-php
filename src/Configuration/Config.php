<?php

declare(strict_types=1);

namespace Churn\Configuration;

/**
 * @internal
 */
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
    private const CACHE_PATH = null;
    private const HOOKS = [];

    /**
     * @var array<string, mixed>
     */
    private $configuration;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @param array<mixed> $configuration Raw config data.
     * @param string|null $path The path of the configuration file if any.
     */
    private function __construct(array $configuration = [], ?string $path = null)
    {
        if ([] !== $configuration) {
            (new Validator())->validateConfigurationValues($configuration);
        }

        $this->configuration = $configuration;
        $this->path = $path;
    }

    /**
     * Create a config with given configuration.
     *
     * @param array<mixed> $configuration The array containing the configuration values.
     * @param string|null $path The path of the configuration file if any.
     */
    public static function create(array $configuration, ?string $path = null): Config
    {
        return new self($configuration, $path);
    }

    /**
     * Create a config with default configuration.
     */
    public static function createFromDefaultValues(): Config
    {
        return new self();
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
     * Get the minimum score a file need to display (ignored if null).
     */
    public function getMinScoreToShow(): ?float
    {
        if (\array_key_exists('minScoreToShow', $this->configuration)) {
            return $this->configuration['minScoreToShow'];
        }

        return self::MINIMUM_SCORE_TO_SHOW;
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

    /**
     * Get the cache file path.
     */
    public function getCachePath(): ?string
    {
        return $this->configuration['cachePath'] ?? self::CACHE_PATH;
    }

    /**
     * Get the hooks.
     *
     * @return array<string>
     */
    public function getHooks(): array
    {
        return $this->configuration['hooks'] ?? self::HOOKS;
    }
}
