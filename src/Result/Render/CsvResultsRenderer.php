<?php declare(strict_types = 1);

namespace Churn\Result\Render;

use function implode;
use Symfony\Component\Console\Output\OutputInterface;

class CsvResultsRenderer implements ResultsRendererInterface
{
    /**
     * Renders the results.
     * @param OutputInterface $output  Output Interface.
     * @param array           $results The results.
     * @return void
     */
    public function render(OutputInterface $output, array $results): void
    {
        $output->writeln($this->getHeader());

        foreach ($results as $result) {
            $output->writeln(implode(';', [ '"'.$result[0].'"', $result[1], $result[2], $result[3] ]));
        };
    }

    /**
     * Get the header.
     * @return string
     */
    private function getHeader(): string
    {
        return '"File";"Times Changed";"Complexity";"Score"';
    }
}
