<?php declare(strict_types = 1);

namespace Churn\Process;

use function array_merge;
use function dirname;
use Churn\File\File;
use function is_callable;
use Phar;
use function strlen;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class ProcessFactory
{
    /**
     * String containing the date of when to look at commits since.
     * @var string
     */
    private $commitsSince;

    /**
     * Executable to run PHP processes.
     * @var string
     */
    private $phpExecutable;

    /**
     * ProcessFactory constructor.
     * @param string $commitsSince String containing the date of when to look at commits since.
     */
    public function __construct(string $commitsSince)
    {
        $this->commitsSince = $commitsSince;
        $this->phpExecutable = (string)(new PhpExecutableFinder())->find();
    }

    /**
     * Creates a process that will count the number of changes for $file.
     * @param File $file File that the process will execute on.
     * @return CountChangesProcess
     */
    public function createCountChangesProcess(File $file): CountChangesProcess
    {
        $process = new Process([
            'git', '-C', dirname($file->getFullPath()), 'rev-list', '--since',
            $this->commitsSince, '--no-merges', '--count', 'HEAD',
            $file->getFullPath(),
            ]);

        return new CountChangesProcess($file, $process);
    }

    /**
     * Creates a Cyclomatic Complexity Process that will run on $file.
     * @param File $file File that the process will execute on.
     * @return CyclomaticComplexityProcess
     */
    public function createCyclomaticComplexityProcess(File $file): CyclomaticComplexityProcess
    {
        $command = array_merge(
            [$this->phpExecutable],
            $this->getAssessorArguments(),
            [$file->getFullPath()]
        );
        $process = new Process($command);

        return new CyclomaticComplexityProcess($file, $process);
    }

    /**
     * @return string[]
     */
    private function getAssessorArguments(): array
    {
        if (is_callable([Phar::class, 'running']) && strlen(Phar::running(false)) > 0) {
            return [Phar::running(false), 'assess-complexity'];
        }

        return [__DIR__ . '/../../bin/CyclomaticComplexityAssessorRunner'];
    }
}
