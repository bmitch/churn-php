<?php

declare(strict_types=1);

namespace Churn\Result\Render;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
class ConsoleResultsRenderer implements ResultsRendererInterface
{

    /**
     * Renders the results.
     *
     * @param OutputInterface $output Output Interface.
     * @param array<array<float|integer|string>> $results The results.
     */
    public function render(OutputInterface $output, array $results): void
    {
        $table = new Table($output);
        $table->setHeaders(['File', 'Times Changed', 'Complexity', 'Score']);
        $table->addRows($results);
        $table->render();
    }
}
