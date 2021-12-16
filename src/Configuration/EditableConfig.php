<?php

declare(strict_types=1);

namespace Churn\Configuration;

/**
 * @internal
 */
final class EditableConfig extends Config
{
    /**
     * @param array<int|string> $unrecognizedKeys The unrecognized keys.
     */
    public function setUnrecognizedKeys(array $unrecognizedKeys): void
    {
        $this->unrecognizedKeys = $unrecognizedKeys;
    }

    /**
     * @param integer $filesToShow The number of files to display in the results table.
     */
    public function setFilesToShow(int $filesToShow): void
    {
        $this->filesToShow = $filesToShow;
    }

    /**
     * @param float|null $minScoreToShow The minimum score for a file to be displayed (ignored if null).
     */
    public function setMinScoreToShow(?float $minScoreToShow): void
    {
        $this->minScoreToShow = $minScoreToShow;
    }

    /**
     * @param float|null $maxScoreThreshold The maximum score threshold.
     */
    public function setMaxScoreThreshold(?float $maxScoreThreshold): void
    {
        $this->maxScoreThreshold = $maxScoreThreshold;
    }

    /**
     * @param string $commitsSince Criteria to apply when counting changes.
     */
    public function setCommitsSince(string $commitsSince): void
    {
        $this->commitsSince = $commitsSince;
    }

    /**
     * @param array<string> $filesToIgnore The files to ignore.
     */
    public function setFilesToIgnore(array $filesToIgnore): void
    {
        $this->filesToIgnore = $filesToIgnore;
    }

    /**
     * @param array<string> $fileExtensions The file extensions to use when processing.
     */
    public function setFileExtensions(array $fileExtensions): void
    {
        $this->fileExtensions = $fileExtensions;
    }

    /**
     * @param string $vcs The version control system.
     */
    public function setVCS(string $vcs): void
    {
        $this->vcs = $vcs;
    }

    /**
     * @param string|null $cachePath The cache file path.
     */
    public function setCachePath(?string $cachePath): void
    {
        $this->cachePath = $cachePath;
    }

    /**
     * @param array<string> $hooks The hooks.
     */
    public function setHooks(array $hooks): void
    {
        $this->hooks = $hooks;
    }
}
