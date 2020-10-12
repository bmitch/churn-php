<?php declare(strict_types = 1);

namespace Churn\Factories;

use Churn\Processes\ChurnProcess;
use Churn\Values\File;
use Symfony\Component\Process\Process;

class ProcessFactory
{
    /**
     * String containing the date of when to look at commits since.
     * @var string
     */
    private $commitsSince;

    /**
     * ProcessFactory constructor.
     * @param string $commitsSince String containing the date of when to look at commits since.
     */
    public function __construct(string $commitsSince)
    {
        $this->commitsSince = $commitsSince;
    }

    /**
     * Creates a Git Commit Process that will run on $file.
     * @param File $file File that the process will execute on.
     * @return ChurnProcess
     */
    public function createGitCommitProcess(File $file): ChurnProcess
    {
        $process = new Process(
            'git -C ' . escapeshellarg(getcwd()) . ' rev-list --since=' .
            escapeshellarg($this->commitsSince) . ' --no-merges --count HEAD ' .
            escapeshellarg($file->getFullPath())
        );

        return new ChurnProcess($file, $process, 'GitCommitProcess');
    }

    /**
     * Creates a Cyclomatic Complexity Process that will run on $file.
     * @param File $file File that the process will execute on.
     * @return ChurnProcess
     */
    public function createCyclomaticComplexityProcess(File $file): ChurnProcess
    {
        $rootFolder = __DIR__ . '/../../bin/';

        $process = new Process(
            'php ' . escapeshellarg($rootFolder . 'CyclomaticComplexityAssessorRunner') .
            ' ' . escapeshellarg($file->getFullPath())
        );

        return new ChurnProcess($file, $process, 'CyclomaticComplexityProcess');
    }
}
