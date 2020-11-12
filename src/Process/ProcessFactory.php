<?php declare(strict_types = 1);

namespace Churn\Process;

use function array_merge;
use Churn\File\File;
use Churn\Process\ChangesCount\GitChangesCountProcess;
use Churn\Process\ChangesCount\NoVcsChangesCountProcess;
use Closure;
use InvalidArgumentException;
use function is_callable;
use Phar;
use function strlen;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class ProcessFactory
{
    /**
     * Builder of objects implementing ChangesCountInterface.
     * @var Closure
     */
    private $changesCountProcessBuilder;

    /**
     * Executable to run PHP processes.
     * @var string
     */
    private $phpExecutable;

    /**
     * Class constructor.
     * @param string $vcs          Name of the version control system.
     * @param string $commitsSince String containing the date of when to look at commits since.
     */
    public function __construct(string $vcs, string $commitsSince)
    {
        $this->changesCountProcessBuilder = $this->getChangesCountProcessBuilder($vcs, $commitsSince);
        $this->phpExecutable = (string)(new PhpExecutableFinder())->find();
    }

    /**
     * Creates a process that will count the number of changes for $file.
     * @param File $file File that the process will execute on.
     * @return ChangesCountInterface
     */
    public function createChangesCountProcess(File $file): ChangesCountInterface
    {
        return ($this->changesCountProcessBuilder)($file);
    }

    /**
     * Creates a Cyclomatic Complexity Process that will run on $file.
     * @param File $file File that the process will execute on.
     * @return CyclomaticComplexityInterface
     */
    public function createCyclomaticComplexityProcess(File $file): CyclomaticComplexityInterface
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
     * @param string $vcs          Name of the version control system.
     * @param string $commitsSince String containing the date of when to look at commits since.
     * @return Closure
     * @throws InvalidArgumentException If VCS is not supported.
     */
    private function getChangesCountProcessBuilder(string $vcs, string $commitsSince): Closure
    {
        switch ($vcs) {
            case 'git':
                return static function (File $file) use ($commitsSince): ChangesCountInterface {
                    return new GitChangesCountProcess($file, $commitsSince);
                };
            case 'none':
                return static function (File $file): ChangesCountInterface {
                    return new NoVcsChangesCountProcess($file);
                };
            default:
                throw new InvalidArgumentException('Unsupported VCS: ' . $vcs);
        }
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
