<?php

declare(strict_types=1);

namespace Churn\Process;

use Churn\File\File;
use Closure;
use Phar;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * @internal
 */
final class ConcreteProcessFactory implements ProcessFactory
{
    /**
     * Builder of objects implementing ChangesCountInterface.
     *
     * @var Closure(File):ChangesCountInterface
     */
    private $changesCountProcessBuilder;

    /**
     * Builder of objects implementing CyclomaticComplexityInterface.
     *
     * @var Closure(File):CyclomaticComplexityInterface
     */
    private $cyclomaticComplexityBuilder;

    /**
     * Class constructor.
     *
     * @param string $vcs Name of the version control system.
     * @param string $commitsSince String containing the date of when to look at commits since.
     */
    public function __construct(string $vcs, string $commitsSince)
    {
        $this->changesCountProcessBuilder = $this->getChangesCountProcessBuilder($vcs, $commitsSince);
        $this->cyclomaticComplexityBuilder = $this->getCyclomaticComplexityProcessBuilder();
    }

    /**
     * @param File $file File that the processes will execute on.
     * @return iterable<ProcessInterface> The list of processes to execute.
     */
    public function createProcesses(File $file): iterable
    {
        $processes = [];
        $processes[] = ($this->changesCountProcessBuilder)($file);
        $processes[] = ($this->cyclomaticComplexityBuilder)($file);

        return $processes;
    }

    /**
     * @param string $vcs Name of the version control system.
     * @param string $commitsSince String containing the date of when to look at commits since.
     * @return Closure(File):ChangesCountInterface
     */
    private function getChangesCountProcessBuilder(string $vcs, string $commitsSince): Closure
    {
        return (new ChangesCountProcessBuilder())->getBuilder($vcs, $commitsSince);
    }

    /**
     * Returns a cyclomatic complexity builder.
     *
     * @return Closure(File):CyclomaticComplexityInterface
     */
    private function getCyclomaticComplexityProcessBuilder(): Closure
    {
        $phpExecutable = $this->getPhpExecutable();
        $command = \array_merge([$phpExecutable], $this->getAssessorArguments());

        return static function (File $file) use ($command): CyclomaticComplexityInterface {
            $command[] = $file->getFullPath();
            $process = new Process($command);

            return new CyclomaticComplexityProcess($file, $process);
        };
    }

    /**
     * @return string The PHP executable.
     */
    private function getPhpExecutable(): string
    {
        $php = 'php';
        $executableFound = (new PhpExecutableFinder())->find();
        if (false !== $executableFound) {
            $php = $executableFound;
        }

        return $php;
    }

    /** @return array<string> */
    private function getAssessorArguments(): array
    {
        $fullPath = '';
        if (\class_exists(Phar::class, false)) {
            $fullPath = Phar::running(false);
        }
        if ('' !== $fullPath) {
            return [$fullPath, 'assess-complexity'];
        }

        return [__DIR__ . '/../../bin/CyclomaticComplexityAssessorRunner'];
    }
}
