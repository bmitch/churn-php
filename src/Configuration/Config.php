<?php declare(strict_types = 1);

namespace Churn\Configuration;

use Webmozart\Assert\Assert;

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
     * Config constructor.
     *
     * @param array<mixed> $configuration Raw config data.
     * @throws \InvalidArgumentException If parameters is badly defined.
     */
    private function __construct(array $configuration = [])
    {
        if (!empty($configuration)) {
            $this->validateConfigurationValues($configuration);
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
     * Get the names of directories to scan.
     *
     * @return array<string>
     */
    public function getDirectoriesToScan(): array
    {
        return $this->configuration['directoriesToScan'] ?? self::DIRECTORIES_TO_SCAN;
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
     * Get how far back in the git history to go to count commits.
     */
    public function getCommitsSince(): string
    {
        return $this->configuration['commitsSince'] ?? self::SHOW_COMMITS_SINCE;
    }

    /**
     * Get the paths to files to ignore when processing.
     *
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
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateConfigurationValues(array $configuration): void
    {
        $this->validateDirectoriesToScan($configuration);
        $this->validateFilesToShow($configuration);
        $this->validateMinScoreToShow($configuration);
        $this->validateParallelJobs($configuration);
        $this->validateCommitsSince($configuration);
        $this->validateFilesToIgnore($configuration);
        $this->validateFileExtensions($configuration);
        $this->validateVCS($configuration);
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateDirectoriesToScan(array $configuration): void
    {
        if (!\array_key_exists('directoriesToScan', $configuration)) {
            return;
        }

        Assert::allString($configuration['directoriesToScan'], 'Directories to scan should be an array of strings');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateFilesToShow(array $configuration): void
    {
        if (!\array_key_exists('filesToShow', $configuration)) {
            return;
        }

        Assert::integer($configuration['filesToShow'], 'Files to show should be an integer');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateMinScoreToShow(array $configuration): void
    {
        if (!\array_key_exists('minScoreToShow', $configuration)) {
            return;
        }

        Assert::numeric($configuration['minScoreToShow'], 'Minimum score to show should be a number');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateParallelJobs(array $configuration): void
    {
        if (!\array_key_exists('parallelJobs', $configuration)) {
            return;
        }

        Assert::integer($configuration['parallelJobs'], 'Amount of parallel jobs should be an integer');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     * @throws \InvalidArgumentException If date is in a bad format.
     */
    private function validateCommitsSince(array $configuration): void
    {
        if (!\array_key_exists('commitsSince', $configuration)) {
            return;
        }

        Assert::string($configuration['commitsSince'], 'Commits since should be a string');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateFilesToIgnore(array $configuration): void
    {
        if (!\array_key_exists('filesToIgnore', $configuration)) {
            return;
        }

        Assert::isArray($configuration['filesToIgnore'], 'Files to ignore should be an array of strings');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateFileExtensions(array $configuration): void
    {
        if (!\array_key_exists('fileExtensions', $configuration)) {
            return;
        }

        Assert::isArray($configuration['fileExtensions'], 'File extensions should be an array of strings');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateVCS(array $configuration): void
    {
        if (!\array_key_exists('vcs', $configuration)) {
            return;
        }

        Assert::string($configuration['vcs'], 'VCS should be a string');
    }
}
