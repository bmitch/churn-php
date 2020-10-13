<?php declare(strict_types = 1);


namespace Churn\Renderers\Results;

use function array_map;
use Churn\Results\ResultCollection;
use function json_encode;
use Symfony\Component\Console\Output\OutputInterface;

class JsonResultsRenderer implements ResultsRendererInterface
{
    /**
     * Renders the results.
     * @param OutputInterface  $output  Output Interface.
     * @param ResultCollection $results Result Collection.
     * @return void
     */
    public function render(OutputInterface $output, ResultCollection $results): void
    {
        $data = array_map(function (array $result) {
            return [
                'file' => $result[0],
                'commits' => $result[1],
                'complexity' => $result[2],
                'score' => $result[3],
            ];
        }, $results->toArray());

        $output->write(json_encode($data));
    }
}
