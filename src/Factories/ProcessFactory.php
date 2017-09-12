<?php declare(strict_types = 1);

namespace Churn\Factories;

use Churn\Processes\ChurnProcess;
use Churn\Values\Config;
use Churn\Values\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Process\ProcessUtils;

class ProcessFactory
{
    /**
     * The config values.
     * @var Config
     */
    private $config;

    /**
     * ProcessFactory constructor.
     * @param Config $config Configuration Settings.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Creates a VCS Commit Process that will run on $file.
     * @param File $file File that the process will execute on.
     * @return ChurnProcess
     */
    public function createVcsCommitProcess(File $file): ChurnProcess
    {
        $process = new Process($this->getVscCommand($file) . ' | sort | uniq -c | sort -nr');

        return new ChurnProcess($file, $process, 'VcsCommitProcess');
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

    private function getVscCommand(File $file): string
    {
        $path = $file->getFullPath();
        while ($dir = dirname($path)) {
            if ($dir == $path) {
                break;
            }

            if (is_dir($dir . '/.git')) {
                return 'git log --since="' . $this->config->getCommitsSince() . '"  --name-only --pretty=format: ' . $file->getFullPath();
            } elseif (is_dir($dir . '/.hg')) {
                $since = date('Y-m-d', strtotime($this->config->getCommitsSince()));
                return 'hg log ' . $file->getFullPath() . ' --date "' . $since . ' to now" --template "' . $file->getDisplayPath() . '\n"';
            }

            $path = $dir;
        }

        throw new \InvalidArgumentException($file->getFullPath() . ' is not located in a known VCS');
    }
}
