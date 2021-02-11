<?php

declare(strict_types=1);

namespace Churn\Process;

use Churn\File\File;
use Closure;
use Phar;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class ConcreteProcessFactory implements ProcessFactory
{

    /**
     * Builder of objects implementing ChangesCountInterface.
     *
     * @var Closure
     */
    private $changesCountProcessBuilder;

    /**
     * Builder of objects implementing CyclomaticComplexityInterface.
     *
     * @var Closure
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
     * @throws InvalidArgumentException If VCS is not supported.
     */
    private function getChangesCountProcessBuilder(string $vcs, string $commitsSince): Closure
    {
        return (new ChangesCountProcessBuilder())->getBuilder($vcs, $commitsSince);
    }

    /**
     * Returns a cyclomatic complexity builder.
     */
    private function getCyclomaticComplexityProcessBuilder(): Closure
    {
        $phpExecutable = (string)(new PhpExecutableFinder())->find();
        $command = \array_merge([$phpExecutable], $this->getAssessorArguments());

        return static function (File $file) use ($command): CyclomaticComplexityInterface {
            $command[] = $file->getFullPath();
            $process = new Process($command);

            return new CyclomaticComplexityProcess($file, $process);
        };
    }

    /** @return array<string> */
    private function getAssessorArguments(): array
    {
        if (\is_callable([Phar::class, 'running']) && '' !== Phar::running(false)) {
            return [Phar::running(false), 'assess-complexity'];
        }

        return [__DIR__ . '/../../bin/CyclomaticComplexityAssessorRunner'];
    }
}
