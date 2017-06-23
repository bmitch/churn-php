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
        $path     = $input->getArgument('path');
        $phpFiles = $this->fileManager->getPhpFiles($path);
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
