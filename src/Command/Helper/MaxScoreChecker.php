<?php

declare(strict_types=1);

namespace Churn\Command\Helper;

use Churn\Result\ResultReporter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
final class MaxScoreChecker
{
    /**
     * @var float|null
     */
    private $maxScoreThreshold;

    /**
     * @param float|null $maxScoreThreshold The max score threshold.
     */
    public function __construct(?float $maxScoreThreshold)
    {
        $this->maxScoreThreshold = $maxScoreThreshold;
    }

    /**
     * @param InputInterface $input Input.
     * @param OutputInterface $output Output.
     * @param ResultReporter $report The report containing the scores.
     */
    public function isOverThreshold(InputInterface $input, OutputInterface $output, ResultReporter $report): bool
    {
        $maxScore = $report->getMaxScore();

        if (null === $this->maxScoreThreshold || null === $maxScore || $maxScore <= $this->maxScoreThreshold) {
            return false;
        }

        $this->printErrorMessage($input, $output);

        return true;
    }

    /**
     * @param InputInterface $input Input.
     * @param OutputInterface $output Output.
     */
    private function printErrorMessage(InputInterface $input, OutputInterface $output): void
    {
        if ('text' !== $input->getOption('format') && '' === (string) $input->getOption('output')) {
            return;
        }

        $output = $output instanceof ConsoleOutputInterface
            ? $output->getErrorOutput()
            : $output;
        $output->writeln('<error>Max score is over the threshold</>');
    }
}
