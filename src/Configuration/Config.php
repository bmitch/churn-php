<?php declare(strict_types = 1);

namespace Churn\Configuration;

use Webmozart\Assert\Assert;

class Config
{

    const FILES_TO_SHOW = 10;
    const MINIMUM_SCORE_TO_SHOW = 0;
    const AMOUNT_OF_PARALLEL_JOBS = 10;
    const SHOW_COMMITS_SINCE = '10 years ago';
    const FILES_TO_IGNORE = [];
    const FILE_EXTENSIONS_TO_PARSE = ['php'];

    /**
     * @var array
     */
    private $configuration;

    /**
     * Create a config with given configuration
     * @param array $configuration The array containing the configuration values.
     * @return Config
     */
    public static function create(array $configuration): Config
    {
        return new self($configuration);
    }

    /**
     * Create a config with default configuration
     * @return Config
     */
    public static function createFromDefaultValues(): Config
    {
        return new self();
    }

    /**
     * Config constructor.
     * @param array $configuration Raw config data.
     * @throws InvalidArgumentException If parameters is badly defined.
     */
    private function __construct(array $configuration = [])
    {
        if (!empty($configuration)) {
            $this->validateConfigurationValues($configuration);
        }

        $this->configuration = $configuration;
    }

    /**
     * Get the number of files to display in the results table.
     * @return integer
     */
    public function getFilesToShow(): int
    {
        return $this->configuration['filesToShow'] ?? self::FILES_TO_SHOW;
    }

    /**
     * Get the minimum score a file need to display.
     * @return integer
     */
    public function getMinScoreToShow(): int
    {
        return $this->configuration['minScoreToShow'] ?? self::MINIMUM_SCORE_TO_SHOW;
    }

    /**
     * Get the number of parallel jobs to use to process the files.
     * @return integer
     */
    public function getParallelJobs(): int
    {
        return $this->configuration['parallelJobs'] ?? self::AMOUNT_OF_PARALLEL_JOBS;
    }

    /**
     * Get how far back in the git history to go to count commits.
     * @return string
     */
    public function getCommitsSince(): string
    {
        return $this->configuration['commitsSince'] ?? self::SHOW_COMMITS_SINCE;
    }

    /**
     * Get the paths to files to ignore when processing.
     * @return array
     */
    public function getFilesToIgnore(): array
    {
        return $this->configuration['filesToIgnore'] ?? self::FILES_TO_IGNORE;
    }

    /**
     * Get the file extensions to use when processing.
     * @return array
     */
    public function getFileExtensions(): array
    {
        return $this->configuration['fileExtensions'] ?? self::FILE_EXTENSIONS_TO_PARSE;
    }

    /**
     * @param array $configuration The array containing the configuration values.
     * @return void
     */
    private function validateConfigurationValues(array $configuration)
    {
        $this->validateFilesToShow($configuration);
        $this->validateMinScoreToShow($configuration);
        $this->validateParallelJobs($configuration);
        $this->validateCommitsSince($configuration);
        $this->validateFilesToIgnore($configuration);
        $this->validateFileExtensions($configuration);
    }

    /**
     * @param array $configuration The array containing the configuration values.
     * @return void
     */
    private function validateFilesToShow(array $configuration)
    {
        if (array_key_exists('filesToShow', $configuration)) {
            Assert::integer($configuration['filesToShow'], 'Files to show should be an integer');
        }
    }

    /**
     * @param array $configuration The array containing the configuration values.
     * @return void
     */
    private function validateMinScoreToShow(array $configuration)
    {
        if (array_key_exists('minScoreToShow', $configuration)) {
            Assert::integer($configuration['minScoreToShow'], 'Minimum score to show should be an integer');
        }
    }

    /**
     * @param array $configuration The array containing the configuration values.
     * @return void
     */
    private function validateParallelJobs(array $configuration)
    {
        if (array_key_exists('parallelJobs', $configuration)) {
            Assert::integer($configuration['parallelJobs'], 'Amount of parallel jobs should be an integer');
        }
    }

    /**
     * @param array $configuration The array containing the configuration values.
     * @return void
     * @throws InvalidArgumentException If date is in a bad format.
     */
    private function validateCommitsSince(array $configuration)
    {
        if (array_key_exists('commitsSince', $configuration)) {
            Assert::string($configuration['commitsSince'], 'Commits since should be a string');
        }
    }

    /**
     * @param array $configuration The array containing the configuration values.
     * @return void
     */
    private function validateFilesToIgnore(array $configuration)
    {
        if (array_key_exists('filesToIgnore', $configuration)) {
            Assert::isArray($configuration['filesToIgnore'], 'Files to ignore should be an array of strings');
        }
    }

    /**
     * @param array $configuration The array containing the configuration values.
     * @return void
     */
    private function validateFileExtensions(array $configuration)
    {
        if (array_key_exists('fileExtensions', $configuration)) {
            Assert::isArray($configuration['fileExtensions'], 'File extensions should be an array of strings');
        }
    }
}
