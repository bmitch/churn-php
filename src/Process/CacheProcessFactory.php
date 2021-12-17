<?php

declare(strict_types=1);

namespace Churn\Process;

use Churn\Event\Event\AfterAnalysis as AfterAnalysisEvent;
use Churn\Event\Event\AfterFileAnalysis as AfterFileAnalysisEvent;
use Churn\Event\Subscriber\AfterAnalysis;
use Churn\Event\Subscriber\AfterFileAnalysis;
use Churn\File\File;
use Churn\File\FileHelper;
use InvalidArgumentException;
use Throwable;

/**
 * @internal
 */
class CacheProcessFactory implements AfterAnalysis, AfterFileAnalysis, ProcessFactory
{
    /**
     * @var string The cache file path.
     */
    private $cachePath;

    /**
     * @var ProcessFactory Inner process factory.
     */
    private $processFactory;

    /**
     * @var array<string, array<scalar>> The cached data.
     */
    private $cache;

    /**
     * @param string $cachePath The cache file path.
     * @param ProcessFactory $processFactory Inner process factory.
     * @throws InvalidArgumentException If the path is invalid.
     */
    public function __construct(string $cachePath, ProcessFactory $processFactory)
    {
        try {
            FileHelper::ensureFileIsWritable($cachePath);
        } catch (Throwable $e) {
            $message = 'Invalid cache file path: ' . $e->getMessage();

            throw new InvalidArgumentException($message, 0, $e);
        }

        $this->cachePath = $cachePath;
        $this->processFactory = $processFactory;
        $this->cache = $this->loadCache($cachePath);
    }

    /**
     * @param File $file File that the processes will execute on.
     * @return iterable<ProcessInterface> The list of processes to execute.
     */
    public function createProcesses(File $file): iterable
    {
        if (!$this->isCached($file)) {
            return $this->processFactory->createProcesses($file);
        }

        $key = $file->getFullPath();

        $countChanges = (int) $this->cache[$key][1];
        $cyclomaticComplexity = (int) $this->cache[$key][2];
        $this->cache[$key][3] = true;

        return [new PredefinedProcess($file, $countChanges, $cyclomaticComplexity)];
    }

    /**
     * @param AfterAnalysisEvent $event The event triggered when the analysis is done.
     */
    public function onAfterAnalysis(AfterAnalysisEvent $event): void
    {
        $this->writeCache();
    }

    /**
     * @param AfterFileAnalysisEvent $event The event triggered when the analysis of a file is done.
     */
    public function onAfterFileAnalysis(AfterFileAnalysisEvent $event): void
    {
        $this->addToCache(
            $event->getFilePath(),
            $event->getNumberOfChanges(),
            $event->getCyclomaticComplexity()
        );
    }

    /**
     * @param string $path The absolute path of the file.
     * @param integer $nbChanges The number of times the file has been changed.
     * @param integer $complexity The cyclomatic complexity of the file.
     */
    private function addToCache(string $path, int $nbChanges, int $complexity): void
    {
        $this->cache[$path][0] = $this->cache[$path][0] ?? \md5_file($path);
        $this->cache[$path][1] = $nbChanges;
        $this->cache[$path][2] = $complexity;
        $this->cache[$path][3] = true;
    }

    /**
     * Write the cache in its file.
     */
    private function writeCache(): void
    {
        $data = [];

        foreach ($this->cache as $path => $values) {
            if (!$values[3]) {
                continue;
            }

            unset($values[3]);
            $data[] = \implode(',', \array_merge([$path], $values));
        }

        \file_put_contents($this->cachePath, \implode("\n", $data));
    }

    /**
     * @param File $file The file to process.
     */
    private function isCached(File $file): bool
    {
        $key = $file->getFullPath();

        if (!isset($this->cache[$key])) {
            return false;
        }

        $md5 = $this->cache[$key][0];
        $this->cache[$key][0] = $newMd5 = \md5_file($file->getFullPath());

        return $md5 === $newMd5;
    }

    /**
     * @param string $cachePath Cache file path.
     * @return array<string, array<scalar>>
     */
    private function loadCache(string $cachePath): array
    {
        if (!\is_file($cachePath)) {
            return [];
        }

        $cache = [];

        foreach (\file($cachePath) as $row) {
            $data = \explode(',', $row);
            $cache[$data[0]] = \array_slice($data, 1);
            $cache[$data[0]][3] = false;
        }

        return $cache;
    }
}
