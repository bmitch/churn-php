<?php

declare(strict_types=1);

namespace Churn\Result\Render;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
final class CsvResultsRenderer implements ResultsRendererInterface
{
    /**
     * Renders the results.
     *
     * @param OutputInterface $output Output Interface.
     * @param array<array<float|integer|string>> $results The results.
     */
    #[\Override]
    public function render(OutputInterface $output, array $results): void
    {
        $output->writeln($this->getHeader());

        foreach ($results as $result) {
            $output->writeln(\implode(';', ['"' . \strval($result[0]) . '"', $result[1], $result[2], $result[3]]));
        }
    }

    /**
     * Get the header.
     */
    private function getHeader(): string
    {
        return '"File";"Times Changed";"Complexity";"Score"';
    }
}
