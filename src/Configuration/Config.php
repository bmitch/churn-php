<?php declare(strict_types = 1);

namespace Churn\Configuration;

use Churn\Configuration\Values\FilesToShow;
use Exception;
use Webmozart\Assert\Assert;

class Config
{

    const FILES_TO_SHOW = 10;
    const MINIMUM_SCORE_TO_SHOW = 0;
    const AMOUNT_OF_PARALLEL_JOBS = 10;
    const SHOW_COMMITS_SINCE = '10 years ago';
    const IGNORE_FILES = [];
    const FILE_EXTENSIONS_TO_PARSE = ['php'];

    /**
     * @var array
     */
    private $configuration;

    /**
     * Create a config with given configuration
     * @param array $configuration
     * @return Config
     */
    public static function create(array $configuration)
    {
        return new self($configuration);
    }

    /**
     * create a config with default configuration
     * @return Config
     */
    public static function createFromDefaultValues()
    {
        return new self([
            'filesToShow' => self::FILES_TO_SHOW,
            'minScoreToShow' => self::MINIMUM_SCORE_TO_SHOW,
            'parallelJobs' => self::AMOUNT_OF_PARALLEL_JOBS,
            'commitsSince' => self::SHOW_COMMITS_SINCE,
            'filesToIgnore' => self::IGNORE_FILES,
            'fileExtensions' => self::FILE_EXTENSIONS_TO_PARSE
        ]);
    }

    /**
     * Config constructor.
     * @param array $configuration Raw config data.
     */
    private function __construct(array $configuration)
    {
        Assert::notEmpty($configuration, 'Configuration should contain elements');

        if (array_key_exists('filesToShow', $configuration)) {
            Assert::integer($configuration['filesToShow'], 'Files to show should be an integer');
        } else {
            $configuration['filesToShow'] = self::FILES_TO_SHOW;
        }

        if (array_key_exists('minScoreToShow', $configuration)) {
            Assert::integer($configuration['minScoreToShow'], 'Minimum score to show should be an integer');
        } else {
            $configuration['minScoreToShow'] = self::MINIMUM_SCORE_TO_SHOW;
        }

        if (array_key_exists('parallelJobs', $configuration)) {
            Assert::integer($configuration['parallelJobs'], 'Amount of parallel jobs should be an integer');
        } else {
            $configuration['parallelJobs'] = self::AMOUNT_OF_PARALLEL_JOBS;
        }

        if (array_key_exists('commitsSince', $configuration)) {
            Assert::string($configuration['commitsSince'], 'Commits since should be a string');
            try {
                new \DateTime($configuration['commitsSince']);
            } catch (Exception $e) {
                throw new \InvalidArgumentException('Commits since should be in a valid date format');
            }
        } else {
            $configuration['commitsSince'] = self::SHOW_COMMITS_SINCE;
        }

        if (array_key_exists('filesToIgnore', $configuration)) {
            Assert::isArray($configuration['filesToIgnore'], 'Files to ignore should be an array of strings');
        } else {
            $configuration['filesToIgnore'] = self::IGNORE_FILES;
        }

        if (array_key_exists('fileExtensions', $configuration)) {
            Assert::isArray($configuration['fileExtensions'], 'File extensions should be an array of strings');
        } else {
            $configuration['fileExtensions'] = self::FILE_EXTENSIONS_TO_PARSE;
        }

        $this->configuration = $configuration;
    }

    /**
     * Get the number of files to display in the results table.
     * @return integer
     */
    public function getFilesToShow(): int
    {
        return $this->configuration['filesToShow'];
    }

    /**
     * Get the minimum score a file need to display.
     * @return integer
     */
    public function getMinScoreToShow(): int
    {
        return $this->configuration['minScoreToShow'];
    }

    /**
     * Get the number of parallel jobs to use to process the files.
     * @return integer
     */
    public function getParallelJobs(): int
    {
        return $this->configuration['parallelJobs'];
    }

    /**
     * Get how far back in the git history to go to count commits.
     * @return string
     */
    public function getCommitsSince(): string
    {
        return $this->configuration['commitsSince'];
    }

    /**
     * Get the paths to files to ignore when processing.
     * @return array
     */
    public function getFilesToIgnore(): array
    {
        return $this->configuration['filesToIgnore'];
    }

    /**
     * Get the file extensions to use when processing.
     * @return array
     */
    public function getFileExtensions(): array
    {
        return $this->configuration['fileExtensions'];
    }
}
