<?php declare(strict_types = 1);

namespace Churn\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Churn\Assessors\GitCommitCount\GitCommitCountAssessor;
use Churn\Assessors\CyclomaticComplexity\CyclomaticComplexityAssessor;
use Churn\Services\CommandService;
use Symfony\Component\Console\Helper\Table;
use Illuminate\Support\Collection;
use SplFileInfo;

class ChurnCommand extends Command
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->commitCountAssessor = new GitCommitCountAssessor(new CommandService);
        $this->complexityAssessor = new CyclomaticComplexityAssessor();
    }

    /**
     * Configure the command
     * @return void
     */
    protected function configure()
    {
        $this->setName('churn')
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

        $directoryIterator = new RecursiveDirectoryIterator($path);

        $results = Collection::make();
        foreach (new RecursiveIteratorIterator($directoryIterator) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $results->push($this->getResults($file));
        }
        $results = $results->sortByDesc('score');
        $this->displayTable($output, $results);
    }

    /**
     * Calculate the results for a file.
     * @param  \SplFileInfo $file File.
     * @return array
     */
    protected function getResults(SplFileInfo $file): array
    {
        $commits    = $this->commitCountAssessor->assess($file->getRealPath());
        $complexity = $this->complexityAssessor->assess($file->getRealPath());

        return [
            'file'       => $file->getRealPath(),
            'commits'    => $commits,
            'complexity' => $complexity,
            'score'      => ($commits/ 10) * ($complexity * 8.75),
        ];
    }

    /**
     * Displays the results in a table.
     * @param  OutputInterface                $output  Output.
     * @param  \Illuminate\Support\Collection $results Results.
     * @return void
     */
    protected function displayTable(OutputInterface $output, Collection $results)
    {
        $table = new Table($output);
        $table->setHeaders(['File', 'Times Changed', 'Complexity', 'Score']);
        foreach ($results as $resultsRow) {
            $table->addRow($resultsRow);
        }
        $table->render();
    }
}
