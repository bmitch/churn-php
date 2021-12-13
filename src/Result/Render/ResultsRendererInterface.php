<?php

declare(strict_types=1);

namespace Churn\Result\Render;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
interface ResultsRendererInterface
{
    /**
     * Renders the results.
     *
     * @param OutputInterface $output Output Interface.
     * @param array<array<float|integer|string>> $results The results.
     */
    public function render(OutputInterface $output, array $results): void;
}
