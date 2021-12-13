<?php

declare(strict_types=1);

namespace Churn\Configuration;

use Webmozart\Assert\Assert;

/**
 * @internal
 */
class Validator
{
    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    public function validateConfigurationValues(array $configuration): void
    {
        $this->validateDirectoriesToScan($configuration);
        $this->validateFilesToShow($configuration);
        $this->validateMinScoreToShow($configuration);
        $this->validateMaxScoreThreshold($configuration);
        $this->validateParallelJobs($configuration);
        $this->validateCommitsSince($configuration);
        $this->validateFilesToIgnore($configuration);
        $this->validateFileExtensions($configuration);
        $this->validateVCS($configuration);
        $this->validateCachePath($configuration);
        $this->validateHooks($configuration);
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateDirectoriesToScan(array $configuration): void
    {
        $key = Config::KEY_DIRECTORIES_TO_SCAN;
        if (!\array_key_exists($key, $configuration)) {
            return;
        }

        Assert::isArray($configuration[$key], 'Directories to scan should be an array of strings');
        Assert::allString($configuration[$key], 'Directories to scan should be an array of strings');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateFilesToShow(array $configuration): void
    {
        $key = Config::KEY_FILES_TO_SHOW;
        if (!\array_key_exists($key, $configuration)) {
            return;
        }

        Assert::integer($configuration[$key], 'Files to show should be an integer');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateMinScoreToShow(array $configuration): void
    {
        $key = Config::KEY_MINIMUM_SCORE_TO_SHOW;
        if (!isset($configuration[$key])) {
            return;
        }

        Assert::numeric($configuration[$key], 'Minimum score to show should be a number');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateMaxScoreThreshold(array $configuration): void
    {
        $key = Config::KEY_MAXIMUM_SCORE_THRESHOLD;
        if (!isset($configuration[$key])) {
            return;
        }

        Assert::numeric($configuration[$key], 'Maximum score threshold should be a number');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateParallelJobs(array $configuration): void
    {
        $key = Config::KEY_AMOUNT_OF_PARALLEL_JOBS;
        if (!\array_key_exists($key, $configuration)) {
            return;
        }

        Assert::integer($configuration[$key], 'Amount of parallel jobs should be an integer');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateCommitsSince(array $configuration): void
    {
        $key = Config::KEY_SHOW_COMMITS_SINCE;
        if (!\array_key_exists($key, $configuration)) {
            return;
        }

        Assert::string($configuration[$key], 'Commits since should be a string');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateFilesToIgnore(array $configuration): void
    {
        $key = Config::KEY_FILES_TO_IGNORE;
        if (!\array_key_exists($key, $configuration)) {
            return;
        }

        Assert::isArray($configuration[$key], 'Files to ignore should be an array of strings');
        Assert::allString($configuration[$key], 'Files to ignore should be an array of strings');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateFileExtensions(array $configuration): void
    {
        $key = Config::KEY_FILE_EXTENSIONS_TO_PARSE;
        if (!\array_key_exists($key, $configuration)) {
            return;
        }

        Assert::isArray($configuration[$key], 'File extensions should be an array of strings');
        Assert::allString($configuration[$key], 'File extensions should be an array of strings');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateVCS(array $configuration): void
    {
        $key = Config::KEY_VCS;
        if (!\array_key_exists($key, $configuration)) {
            return;
        }

        Assert::string($configuration[$key], 'VCS should be a string');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateCachePath(array $configuration): void
    {
        $key = Config::KEY_CACHE_PATH;
        if (!isset($configuration[$key])) {
            return;
        }

        Assert::string($configuration[$key], 'Cache path should be a string');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateHooks(array $configuration): void
    {
        $key = Config::KEY_HOOKS;
        if (!\array_key_exists($key, $configuration)) {
            return;
        }

        Assert::isArray($configuration[$key], 'Hooks should be an array of strings');
        Assert::allString($configuration[$key], 'Hooks should be an array of strings');
    }
}
