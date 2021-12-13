<?php

declare(strict_types=1);

namespace Churn\Configuration;

/**
 * @internal
 */
class Config
{
    public const KEY_DIRECTORIES_TO_SCAN = 'directoriesToScan';
    public const KEY_FILES_TO_SHOW = 'filesToShow';
    public const KEY_MINIMUM_SCORE_TO_SHOW = 'minScoreToShow';
    public const KEY_MAXIMUM_SCORE_THRESHOLD = 'maxScoreThreshold';
    public const KEY_AMOUNT_OF_PARALLEL_JOBS = 'parallelJobs';
    public const KEY_SHOW_COMMITS_SINCE = 'commitsSince';
    public const KEY_FILES_TO_IGNORE = 'filesToIgnore';
    public const KEY_FILE_EXTENSIONS_TO_PARSE = 'fileExtensions';
    public const KEY_VCS = 'vcs';
    public const KEY_CACHE_PATH = 'cachePath';
    public const KEY_HOOKS = 'hooks';

    private const DIRECTORIES_TO_SCAN = [];
    private const FILES_TO_SHOW = 10;
    private const MINIMUM_SCORE_TO_SHOW = 0.1;
    private const MAXIMUM_SCORE_THRESHOLD = null;
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
     * @return array<string> The unrecognized keys.
     */
    public function getUnrecognizedKeys(): array
    {
        $knownKeys = [
            self::KEY_DIRECTORIES_TO_SCAN => null,
            self::KEY_FILES_TO_SHOW => null,
            self::KEY_MINIMUM_SCORE_TO_SHOW => null,
            self::KEY_MAXIMUM_SCORE_THRESHOLD => null,
            self::KEY_AMOUNT_OF_PARALLEL_JOBS => null,
            self::KEY_SHOW_COMMITS_SINCE => null,
            self::KEY_FILES_TO_IGNORE => null,
            self::KEY_FILE_EXTENSIONS_TO_PARSE => null,
            self::KEY_VCS => null,
            self::KEY_CACHE_PATH => null,
            self::KEY_HOOKS => null,
        ];

        return \array_keys(\array_diff_key($this->configuration, $knownKeys));
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
        return $this->configuration[self::KEY_DIRECTORIES_TO_SCAN] ?? self::DIRECTORIES_TO_SCAN;
    }

    /**
     * @param array<string> $directories Paths of directories to scan.
     */
    public function setDirectoriesToScan(array $directories): void
    {
        $this->configuration[self::KEY_DIRECTORIES_TO_SCAN] = $directories;
    }

    /**
     * Get the number of files to display in the results table.
     */
    public function getFilesToShow(): int
    {
        return $this->configuration[self::KEY_FILES_TO_SHOW] ?? self::FILES_TO_SHOW;
    }

    /**
     * Get the minimum score a file need to display (ignored if null).
     */
    public function getMinScoreToShow(): ?float
    {
        if (\array_key_exists(self::KEY_MINIMUM_SCORE_TO_SHOW, $this->configuration)) {
            return $this->configuration[self::KEY_MINIMUM_SCORE_TO_SHOW];
        }

        return self::MINIMUM_SCORE_TO_SHOW;
    }

    /**
     * Get the maximum score threshold.
     */
    public function getMaxScoreThreshold(): ?float
    {
        if (\array_key_exists(self::KEY_MAXIMUM_SCORE_THRESHOLD, $this->configuration)) {
            return $this->configuration[self::KEY_MAXIMUM_SCORE_THRESHOLD];
        }

        return self::MAXIMUM_SCORE_THRESHOLD;
    }

    /**
     * Get the number of parallel jobs to use to process the files.
     */
    public function getParallelJobs(): int
    {
        return $this->configuration[self::KEY_AMOUNT_OF_PARALLEL_JOBS] ?? self::AMOUNT_OF_PARALLEL_JOBS;
    }

    /**
     * @param integer $parallelJobs Number of parallel jobs.
     */
    public function setParallelJobs(int $parallelJobs): void
    {
        $this->configuration[self::KEY_AMOUNT_OF_PARALLEL_JOBS] = $parallelJobs;
    }

    /**
     * Get how far back in the git history to go to count commits.
     */
    public function getCommitsSince(): string
    {
        return $this->configuration[self::KEY_SHOW_COMMITS_SINCE] ?? self::SHOW_COMMITS_SINCE;
    }

    /**
     * @return array<string>
     */
    public function getFilesToIgnore(): array
    {
        return $this->configuration[self::KEY_FILES_TO_IGNORE] ?? self::FILES_TO_IGNORE;
    }

    /**
     * Get the file extensions to use when processing.
     *
     * @return array<string>
     */
    public function getFileExtensions(): array
    {
        return $this->configuration[self::KEY_FILE_EXTENSIONS_TO_PARSE] ?? self::FILE_EXTENSIONS_TO_PARSE;
    }

    /**
     * Get the version control system.
     */
    public function getVCS(): string
    {
        return $this->configuration[self::KEY_VCS] ?? self::VCS;
    }

    /**
     * Get the cache file path.
     */
    public function getCachePath(): ?string
    {
        return $this->configuration[self::KEY_CACHE_PATH] ?? self::CACHE_PATH;
    }

    /**
     * Get the hooks.
     *
     * @return array<string>
     */
    public function getHooks(): array
    {
        return $this->configuration[self::KEY_HOOKS] ?? self::HOOKS;
    }
}
