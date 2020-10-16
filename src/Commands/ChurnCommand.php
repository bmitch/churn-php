<?php declare(strict_types = 1);

namespace Churn\Commands;

use Churn\Factories\ResultsRendererFactory;
use Churn\Logic\ResultsLogic;
use Churn\Managers\FileManager;
use Churn\Process\ProcessFactory;
use Churn\Process\ProcessHandlerFactory;
use function count;
use function file_get_contents;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Churn\Configuration\Config;
use Symfony\Component\Yaml\Yaml;
use InvalidArgumentException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ChurnCommand extends Command
{
    /**
     * The results logic.
     * @var ResultsLogic
     */
    private $resultsLogic;

    /**
     * The process handler factory.
     * @var ProcessHandlerFactory
     */
    private $processHandlerFactory;

    /**
     * The renderer factory.
     * @var ResultsRendererFactory
     */
    private $renderFactory;

    /**
     * ChurnCommand constructor.
     * @param ResultsLogic           $resultsLogic          The results logic.
     * @param ProcessHandlerFactory  $processHandlerFactory The process handler factory.
     * @param ResultsRendererFactory $renderFactory         The Results Renderer Factory.
     */
    public function __construct(
        ResultsLogic $resultsLogic,
        ProcessHandlerFactory $processHandlerFactory,
        ResultsRendererFactory $renderFactory
    ) {
        parent::__construct();
        $this->resultsLogic = $resultsLogic;
        $this->processHandlerFactory = $processHandlerFactory;
        $this->renderFactory = $renderFactory;
    }

    /**
     * Configure the command
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('run')
            ->addArgument('paths', InputArgument::IS_ARRAY, 'Path to source to check.')
            ->addOption('configuration', 'c', InputOption::VALUE_OPTIONAL, 'Path to the configuration file', 'churn.yml')  // @codingStandardsIgnoreLine
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'The output format to use', 'text')
            ->setDescription('Check files')
            ->setHelp('Checks the churn on the provided path argument(s).');
    }

    /**
     * Execute the command
     * @param  InputInterface  $input  Input.
     * @param  OutputInterface $output Output.
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $content = (string) @file_get_contents($input->getOption('configuration'));
        $config = Config::create(Yaml::parse($content) ?? []);
        $filesCollection = (new FileManager($config->getFileExtensions(), $config->getFilesToIgnore()))
            ->getPhpFiles($this->getDirectoriesToScan($input, $config->getDirectoriesToScan()));

        $completedProcesses = $this->processHandlerFactory->getProcessHandler($config)->process(
            $filesCollection,
            new ProcessFactory($config->getCommitsSince()),
            $config->getParallelJobs()
        );
        $resultCollection = $this->resultsLogic->process(
            $completedProcesses,
            $config->getMinScoreToShow(),
            $config->getFilesToShow()
        );

        $renderer = $this->renderFactory->getRenderer($input->getOption('format'));
        $renderer->render($output, $resultCollection);
        return 0;
    }

    /**
     * Get the directories to scan.
     * @param InputInterface $input          Input Interface.
     * @param array          $dirsConfigured The directories configured to scan.
     * @throws InvalidArgumentException If paths argument invalid.
     * @return array When no directories to scan found.
     */
    private function getDirectoriesToScan(InputInterface $input, array $dirsConfigured): array
    {
        $dirsProvidedAsArgs = $input->getArgument('paths');
        if (count($dirsProvidedAsArgs) > 0) {
            return $dirsProvidedAsArgs;
        }

        if (count($dirsConfigured) > 0) {
            return $dirsConfigured;
        }

        throw new InvalidArgumentException(
            'Provide the directories you want to scan as arguments, ' .
            'or configure them under "directoriesToScan" in your churn.yml file.'
        );
    }
}
