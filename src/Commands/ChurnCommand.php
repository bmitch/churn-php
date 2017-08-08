<?php declare(strict_types = 1);

namespace Churn\Commands;

use Churn\Services\CommandService;
use Churn\Assessors\GitCommitCount\GitCommitCountAssessor;
use Churn\Assessors\CyclomaticComplexity\CyclomaticComplexityAssessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\Table;
use Churn\Results\ResultsGenerator;
use Churn\Managers\FileManager;
use Churn\Results\ResultCollection;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpProcess;
use Churn\Processes\Output\GitProcessOutput;
use Churn\Processes\Output\CyclomaticComplexityProcessOutput;
use Illuminate\Support\Collection;

class ChurnCommand extends Command
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $commitCountAssessor    = new GitCommitCountAssessor(new CommandService);
        $complexityAssessor     = new CyclomaticComplexityAssessor();
        $this->resultsGenerator = new ResultsGenerator($commitCountAssessor, $complexityAssessor);
        $this->fileManager      = new FileManager;
    }

    /**
     * Configure the command
     * @return void
     */
    protected function configure()
    {
        $this->setName('run')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to source to check.')
            ->setDescription('Check files')
            ->setHelp('Checks the churn on the provided path argument(s).');
    }

    /**
     * Exectute the command
     * @param  InputInterface  $input  Input.
     * @param  OutputInterface $output Output.
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');
        $files = $this->fileManager->getPhpFiles($path);
        $processes = new Collection;
        // foreach ($files as $file) {
        //     echo ".";
        //     $folder = $file->getFolder();
        //     $fullPath = $file->getFullPath();
        //     $displayPath = $file->getDisplayPath();

        //     $command = "cd {$folder} && git log --name-only --pretty=format: {$fullPath} | sort | uniq -c | sort -nr";
        //     $process = new Process($command);
        //     // $process->run();
        //     $processes->push(['file' => $displayPath, 'type' => 'git', 'process' => $process]);

        //     $rootFolder = __DIR__ . '/../../';
        //     $command = "php {$rootFolder}CyclomaticComplexityAssessorRunner {$fullPath}";
        //     $process = new Process($command);
        //     // $process->run();
        //     $processes->push(['file' => $displayPath, 'type' => 'complexity', 'process' => $process]);
        // }

        $running = [];
        $output = [];
        while ($files || $running) {
            for ($index = count($running); $files && $index < 10; $index++) {
                echo ".";
                $file = array_shift($files);
                $folder = $file->getFolder();
                $fullPath = $file->getFullPath();
                // $displayPath = $file->getDisplayPath();

                $command = "git -C /home/bmitch/Code/nn4m/startrite/vendor/nn4m-clients/sr/ log --name-only --pretty=format: {$fullPath} | sort | uniq -c | sort -nr";
                // dd($command);
                $process = new Process($command);
                $process->start();
                $running[$fullPath] = $process;

                $rootFolder = __DIR__ . '/../../';
                $command = "php {$rootFolder}CyclomaticComplexityAssessorRunner {$fullPath}";
                $process = new Process($command);
                $process->start();
                $running[$fullPath . 'a'] = $process;
            }

            foreach ($running as $file => $process) {
                if ($process->isSuccessful()) {
                    unset($running[$file]);
                    $output[] = new GitProcessOutput($process->getOutput());
                }
                // if ($process['complexity']->isSuccessful()) {
                //     // unset($running[$file]['complexity']);
                //     $output[$file]['complexity'] = new CyclomaticComplexityProcessOutput($process['complexity']->getOutput());
                // }
            }

        }
        dd($output);
        $results  = $this->resultsGenerator->getResults($phpFiles);
        $this->displayResults($output, $results);
    }

    /**
     * Displays the results in a table.
     * @param  OutputInterface                $output  Output.
     * @param  Churn\Results\ResultCollection $results Results Collection.
     * @return void
     */
    protected function displayResults(OutputInterface $output, ResultCollection $results)
    {
        $table = new Table($output);
        $table->setHeaders(['File', 'Times Changed', 'Complexity', 'Score']);
        foreach ($results->orderByScoreDesc() as $result) {
            $table->addRow($result->toArray());
        }
        $table->render();
    }
}
