<?php declare(strict_types = 1);

namespace Churn\Factories;

use Churn\Processes\ChurnProcess;
use Churn\Values\File;
use Symfony\Component\Process\Process;

class ProcessFactory
{
    /**
     * Creates a Git Commit Process that will run on $file.
     * @param File $file File that the process will execute on.
     * @return ChurnProcess
     */
    public function createGitCommitProcess(File $file): ChurnProcess
    {
        $process = new Process(
            'git -C ' . getcwd() . " log --name-only --pretty=format: " . $file->getFullPath(). " | sort | uniq -c | sort -nr"
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
        $rootFolder = __DIR__ . '/../../';

        $process = new Process(
            "php {$rootFolder}CyclomaticComplexityAssessorRunner {$file->getFullPath()}"
        );

        return new ChurnProcess($file, $process, 'CyclomaticComplexityProcess');
    }
}
