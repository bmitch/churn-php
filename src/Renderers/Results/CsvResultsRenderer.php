<?php declare(strict_types = 1);

namespace Churn\Renderers\Results;

use Churn\Results\ResultCollection;
use Symfony\Component\Console\Output\OutputInterface;

class CsvResultsRenderer implements ResultsRendererInterface
{
    /**
     * Renders the results.
     * @param OutputInterface  $output  Output Interface.
     * @param ResultCollection $results Result Collection.
     * @return void
     */
    public function render(OutputInterface $output, ResultCollection $results)
    {
        $output->writeln($this->getHeader());

        foreach ($results->toArray() as $result) {
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
