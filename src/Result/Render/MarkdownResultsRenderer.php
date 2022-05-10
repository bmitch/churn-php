<?php

declare(strict_types=1);

namespace Churn\Result\Render;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
final class MarkdownResultsRenderer implements ResultsRendererInterface
{
    /**
     * Renders the results.
     *
     * @param OutputInterface $output Output Interface.
     * @param array<array<float|integer|string>> $results The results.
     */
    public function render(OutputInterface $output, array $results): void
    {
        $output->writeln('| File | Times Changed | Complexity | Score |');
        $output->writeln('|------|---------------|------------|-------|');

        foreach ($results as $result) {
            $output->writeln($this->inline($result));
        }
    }

    /**
     * @param array<float|integer|string> $data The data to inline.
     */
    private function inline(array $data): string
    {
        $escapedData = \array_map(static function ($item) {
            return \is_string($item)
                ? \str_replace('|', '\\|', $item)
                : $item;
        }, $data);

        return '| ' . \implode(' | ', $escapedData) . ' |';
    }
}
