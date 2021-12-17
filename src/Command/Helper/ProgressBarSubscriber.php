<?php

declare(strict_types=1);

namespace Churn\Command\Helper;

use Churn\Event\Event\AfterAnalysis as AfterAnalysisEvent;
use Churn\Event\Event\AfterFileAnalysis as AfterFileAnalysisEvent;
use Churn\Event\Event\BeforeAnalysis as BeforeAnalysisEvent;
use Churn\Event\Subscriber\AfterAnalysis;
use Churn\Event\Subscriber\AfterFileAnalysis;
use Churn\Event\Subscriber\BeforeAnalysis;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
class ProgressBarSubscriber implements AfterAnalysis, AfterFileAnalysis, BeforeAnalysis
{
    /**
     * @var ProgressBar
     */
    private $progressBar;

    /**
     * @param OutputInterface $output The console output.
     */
    public function __construct(OutputInterface $output)
    {
        $this->progressBar = new ProgressBar($output);
    }

    /**
     * @param AfterAnalysisEvent $event The event triggered when the analysis is done.
     */
    public function onAfterAnalysis(AfterAnalysisEvent $event): void
    {
        $this->progressBar->finish();
    }

    /**
     * @param BeforeAnalysisEvent $event The event triggered when the analysis starts.
     */
    public function onBeforeAnalysis(BeforeAnalysisEvent $event): void
    {
        $this->progressBar->start();
    }

    /**
     * @param AfterFileAnalysisEvent $event The event triggered when the analysis of a file is done.
     */
    public function onAfterFileAnalysis(AfterFileAnalysisEvent $event): void
    {
        $this->progressBar->advance();
    }
}
