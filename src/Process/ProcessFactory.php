<?php declare(strict_types = 1);

namespace Churn\Process;

use function array_merge;
use Churn\File\File;
use function getcwd;
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
        $process = new Process([
            'git', '-C', getcwd(), 'rev-list', '--since',
            $this->commitsSince, '--no-merges', '--count', 'HEAD',
            $file->getFullPath(),
            ]);

        return new ChurnProcess($file, $process, 'GitCommitProcess');
    }

    /**
     * Creates a Cyclomatic Complexity Process that will run on $file.
     * @param File $file File that the process will execute on.
     * @return ChurnProcess
     */
    public function createCyclomaticComplexityProcess(File $file): ChurnProcess
    {
        $command = array_merge(
            [(new PhpExecutableFinder())->find()],
            $this->getAssessorArguments(),
            [$file->getFullPath()]
        );
        $process = new Process($command);

        return new ChurnProcess($file, $process, 'CyclomaticComplexityProcess');
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
