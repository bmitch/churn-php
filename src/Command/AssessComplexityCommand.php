<?php

declare(strict_types=1);

namespace Churn\Command;

use Churn\Assessor\Assessor;
use Churn\Assessor\CyclomaticComplexityAssessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
final class AssessComplexityCommand extends Command
{
    /**
     * @var Assessor
     */
    private $assessor;

    /**
     * Class constructor.
     *
     * @param Assessor $assessor The class calculating the complexity.
     */
    public function __construct(Assessor $assessor)
    {
        parent::__construct();

        $this->assessor = $assessor;
    }

    /**
     * Returns a new instance of the command.
     */
    public static function newInstance(): self
    {
        return new self(new CyclomaticComplexityAssessor());
    }

    /**
     * Configure the command
     */
    #[\Override]
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
    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = (string) $input->getArgument('file');
        $contents = \is_file($file)
            ? \file_get_contents($file)
            : false;

        if (false === $contents) {
            $output->writeln('0');

            return 0;
        }

        $output->writeln((string) $this->assessor->assess($contents));

        return 0;
    }
}
