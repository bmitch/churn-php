<?php

declare(strict_types=1);

namespace Churn\Process;

use Churn\File\File;
use Churn\Process\ChangesCount\GitChangesCountProcess;
use Churn\Process\ChangesCount\MercurialChangesCountProcess;
use Churn\Process\ChangesCount\NoVcsChangesCountProcess;
use Closure;
use InvalidArgumentException;
use Phar;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class ProcessFactory
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
     * Creates a process that will count the number of changes for $file.
     *
     * @param File $file File that the process will execute on.
     */
    public function createChangesCountProcess(File $file): ChangesCountInterface
    {
        return ($this->changesCountProcessBuilder)($file);
    }

    /**
     * Creates a Cyclomatic Complexity Process that will run on $file.
     *
     * @param File $file File that the process will execute on.
     */
    public function createCyclomaticComplexityProcess(File $file): CyclomaticComplexityInterface
    {
        return ($this->cyclomaticComplexityBuilder)($file);
    }

    /**
     * @param string $vcs Name of the version control system.
     * @param string $commitsSince String containing the date of when to look at commits since.
     * @throws InvalidArgumentException If VCS is not supported.
     */
    private function getChangesCountProcessBuilder(string $vcs, string $commitsSince): Closure
    {
        if ('git' === $vcs) {
            return $this->getGitChangesCountProcessBuilder($commitsSince);
        }

        if ('mercurial' === $vcs) {
            return $this->getMercurialChangesCountProcessBuilder($commitsSince);
        }

        if ('none' === $vcs) {
            return static function (File $file): ChangesCountInterface {
                return new NoVcsChangesCountProcess($file);
            };
        }

        throw new InvalidArgumentException('Unsupported VCS: ' . $vcs);
    }

    /**
     * @param string $commitsSince String containing the date of when to look at commits since.
     */
    private function getGitChangesCountProcessBuilder(string $commitsSince): Closure
    {
        return static function (File $file) use ($commitsSince): ChangesCountInterface {
            return new GitChangesCountProcess($file, $commitsSince);
        };
    }

    /**
     * @param string $commitsSince String containing the date of when to look at commits since.
     */
    private function getMercurialChangesCountProcessBuilder(string $commitsSince): Closure
    {
        $date = \date('Y-m-d', \strtotime($commitsSince));

        return static function (File $file) use ($date): ChangesCountInterface {
            return new MercurialChangesCountProcess($file, $date);
        };
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
