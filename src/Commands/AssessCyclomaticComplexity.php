<?php
declare(strict_types=1);

namespace Churn\Commands;

use Churn\Assessors\CyclomaticComplexity\CyclomaticComplexityAssessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AssessCyclomaticComplexity extends Command
{
    /**
     * Configure the command
     * @return void
     */
    protected function configure()
    {
        $this->setName('assess-complexity')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to source to assess.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('path');
        $assessor = new CyclomaticComplexityAssessor();
        echo $assessor->assess($file);
    }
}
