<?php

declare(strict_types=1);

namespace Churn\Command;

use Churn\Assessor\CyclomaticComplexityAssessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AssessComplexityCommand extends Command
{

    /**
     * Configure the command
     */
    protected function configure(): void
    {
        $this->setName('assess-complexity')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to file to analyze.')
            ->setDescription('Calculate the Cyclomatic Complexity');
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input Input.
     * @param OutputInterface $output Output.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getArgument('file');
        $assessor = new CyclomaticComplexityAssessor();
        $output->writeln((string) $assessor->assess($file));

        return 0;
    }
}
