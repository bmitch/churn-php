<?php declare(strict_types = 1);

namespace Churn\Result\Render;

use Symfony\Component\Console\Output\OutputInterface;

interface ResultsRendererInterface
{
    /**
     * Renders the results.
     * @param OutputInterface $output  Output Interface.
     * @param array           $results The results.
     * @return void
     */
    public function render(OutputInterface $output, array $results): void;
}
