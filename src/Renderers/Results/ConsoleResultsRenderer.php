<?php declare(strict_types = 1);

namespace Churn\Renderers\Results;

use Churn\Results\ResultCollection;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleResultsRenderer implements ResultsRendererInterface
{
    /**
     * Renders the results.
     * @param OutputInterface  $output  Output Interface.
     * @param ResultCollection $results Result Collection.
     * @return void
     */
    public function render(OutputInterface $output, ResultCollection $results): void
    {
        $output->write($this->getHeader());

        $table = new Table($output);
        $table->setHeaders(['File', 'Times Changed', 'Complexity', 'Score']);
        $table->addRows($results->toArray());
        $table->render();

        $output->write("\n");
    }

    /**
     * Get the header.
     * @return string
     */
    private function getHeader(): string
    {
        return "\n
    ___  _   _  __  __  ____  _  _     ____  _   _  ____
   / __)( )_( )(  )(  )(  _ \( \( )___(  _ \( )_( )(  _ \
  ( (__  ) _ (  )(__)(  )   / )  ((___))___/ ) _ (  )___/
   \___)(_) (_)(______)(_)\_)(_)\_)   (__)  (_) (_)(__)      https://github.com/bmitch/churn-php\n\n";
    }
}
