<?php declare(strict_types = 1);

namespace Churn\Commands;

use Churn\Processes\CyclomaticComplexityProcess;
use Churn\Processes\GitCommitCountProcess;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\Table;
use Churn\Managers\FileManager;
use Churn\Results\ResultCollection;
use Illuminate\Support\Collection;
use Churn\Results\ResultsParser;

class ChurnCommand extends Command
{
    /**
     * The file manager.
     * @var FileManager
     */
    private $fileManager;

    /**
     * Th results parser.
     * @var ResultsParser
     */
    private $resultsParser;

    /**
     * Collection of files to run the processes on.
     * @var Collection
     */
    private $filesCollection;

    /**
     * Collection of processes currently running.
     * @var Collection
     */
    private $runningProcesses;

    /**
     * Array of completed processes.
     * @var array
     */
    private $completedProcessesArray;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->fileManager = new FileManager;
        $this->resultsParser = new ResultsParser;
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
        $this->filesCollection = $this->fileManager->getPhpFiles($path);

        $this->runningProcesses = new Collection;
        $this->completedProcessesArray = [];
        while ($this->filesCollection->hasFiles() || $this->runningProcesses->count()) {
            $this->getProcessResults();
        }
        $completedProcesses = new Collection($this->completedProcessesArray);

        $results = $this->resultsParser->parse($completedProcesses);
        $this->displayResults($output, $results);
    }

    /**
     * Gets the output from processes and stores them in the completedProcessArray member.
     * @return void
     */
    private function getProcessResults()
    {
        for ($index = $this->runningProcesses->count(); $this->filesCollection->hasFiles() > 0 && $index < 10; $index++) {
            $file = $this->filesCollection->getNextFile();

            $process = new GitCommitCountProcess($file);
            $process->start();
            $this->runningProcesses->put($process->getKey(), $process);
            $process = new CyclomaticComplexityProcess($file);
            $process->start();
            $this->runningProcesses->put($process->getKey(), $process);
        }

        foreach ($this->runningProcesses as $file => $process) {
            if ($process->isSuccessful()) {
                $this->runningProcesses->forget($process->getKey());
                $this->completedProcessesArray[$process->getFileName()][$process->getType()] = $process;
            }
        }
    }

    /**
     * Displays the results in a table.
     * @param  OutputInterface                $output  Output.
     * @param  Churn\Results\ResultCollection $results Results Collection.
     * @return void
     */
    protected function displayResults(OutputInterface $output, ResultCollection $results)
    {
        echo "\n
  ___  _   _  __  __  ____  _  _     ____  _   _  ____ 
 / __)( )_( )(  )(  )(  _ \( \( )___(  _ \( )_( )(  _ \
( (__  ) _ (  )(__)(  )   / )  ((___))___/ ) _ (  )___/
 \___)(_) (_)(______)(_)\_)(_)\_)   (__)  (_) (_)(__)      https://github.com/bmitch/churn-php
 
";
        $table = new Table($output);
        $table->setHeaders(['File', 'Times Changed', 'Complexity', 'Score']);

        foreach ($results->orderByScoreDesc()->take(10) as $result) {
            $table->addRow($result->toArray());
        }
        $table->render();
    }
}
