<?php declare(strict_types = 1);


namespace Churn\Renderers\Results;

use Churn\Results\ResultCollection;
use Symfony\Component\Console\Output\OutputInterface;

interface ResultsRendererInterface
{
    /**
     * Renders the results.
     * @param OutputInterface  $output  Output Interface.
     * @param ResultCollection $results Result Collection.
     * @return void
     */
    public function render(OutputInterface $output, ResultCollection $results): void;
}
