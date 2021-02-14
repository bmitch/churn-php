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
        if (!\array_key_exists('directoriesToScan', $configuration)) {
            return;
        }

        Assert::isArray($configuration['directoriesToScan'], 'Directories to scan should be an array of strings');
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
        if (!\array_key_exists('minScoreToShow', $configuration) || null === $configuration['minScoreToShow']) {
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
        Assert::allString($configuration['filesToIgnore'], 'Files to ignore should be an array of strings');
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
        Assert::allString($configuration['fileExtensions'], 'File extensions should be an array of strings');
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

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateCachePath(array $configuration): void
    {
        if (!isset($configuration['cachePath'])) {
            return;
        }

        Assert::string($configuration['cachePath'], 'Cache path should be a string');
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function validateHooks(array $configuration): void
    {
        if (!\array_key_exists('hooks', $configuration)) {
            return;
        }

        Assert::isArray($configuration['hooks'], 'Hooks should be an array of strings');
        Assert::allString($configuration['hooks'], 'Hooks should be an array of strings');
    }
}
