<?php

declare(strict_types=1);

namespace Churn\Command;

use Churn\Configuration\Config;
use Churn\Configuration\Loader;
use Churn\File\FileFinder;
use Churn\Process\Observer\OnSuccess;
use Churn\Process\Observer\OnSuccessAccumulate;
use Churn\Process\Observer\OnSuccessCollection;
use Churn\Process\Observer\OnSuccessProgress;
use Churn\Process\ProcessFactory;
use Churn\Process\ProcessHandlerFactory;
use Churn\Result\ResultAccumulator;
use Churn\Result\ResultsRendererFactory;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Webmozart\Assert\Assert;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class RunCommand extends Command
{

    public const LOGO = "
    ___  _   _  __  __  ____  _  _     ____  _   _  ____
   / __)( )_( )(  )(  )(  _ \( \( )___(  _ \( )_( )(  _ \
  ( (__  ) _ (  )(__)(  )   / )  ((___))___/ ) _ (  )___/
   \___)(_) (_)(______)(_)\_)(_)\_)   (__)  (_) (_)(__)
";

    /**
     * The process handler factory.
     *
     * @var ProcessHandlerFactory
     */
    private $processHandlerFactory;

    /**
     * The renderer factory.
     *
     * @var ResultsRendererFactory
     */
    private $renderFactory;

    /**
     * Class constructor.
     *
     * @param ProcessHandlerFactory $processHandlerFactory The process handler factory.
     * @param ResultsRendererFactory $renderFactory The Results Renderer Factory.
     */
    public function __construct(ProcessHandlerFactory $processHandlerFactory, ResultsRendererFactory $renderFactory)
    {
        parent::__construct();

        $this->processHandlerFactory = $processHandlerFactory;
        $this->renderFactory = $renderFactory;
    }

    /**
     * Returns a new instance of the command.
     */
    public static function newInstance(): self
    {
        return new self(new ProcessHandlerFactory(), new ResultsRendererFactory());
    }

    /**
     * Configure the command
     */
    protected function configure(): void
    {
        $this->setName('run')
            ->addArgument('paths', InputArgument::IS_ARRAY, 'Path to source to check.')
            ->addOption('configuration', 'c', InputOption::VALUE_REQUIRED, 'Path to the configuration file', 'churn.yml') // @codingStandardsIgnoreLine
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'The output format to use', 'text')
            ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'The path where to write the result')
            ->addOption('parallel', null, InputOption::VALUE_REQUIRED, 'Number of parallel jobs')
            ->addOption('progress', 'p', InputOption::VALUE_NONE, 'Show progress bar')
            ->setDescription('Check files')
            ->setHelp('Checks the churn on the provided path argument(s).');
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input Input.
     * @param OutputInterface $output Output.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->displayLogo($input, $output);
        $accumulator = $this->analyze($input, $output, $this->getConfiguration($input));
        $this->writeResult($input, $output, $accumulator);

        return 0;
    }

    /**
     * @param InputInterface $input Input.
     * @throws InvalidArgumentException If paths argument invalid.
     */
    private function getConfiguration(InputInterface $input): Config
    {
        $config = Loader::fromPath((string) $input->getOption('configuration'));

        if ([] !== $input->getArgument('paths')) {
            $config->setDirectoriesToScan($input->getArgument('paths'));
        }

        if ([] === $config->getDirectoriesToScan()) {
            throw new InvalidArgumentException(
                'Provide the directories you want to scan as arguments, ' .
                'or configure them under "directoriesToScan" in your churn.yml file.'
            );
        }

        if (null !== $input->getOption('parallel')) {
            Assert::integerish($input->getOption('parallel'), 'Amount of parallel jobs should be an integer');
            $config->setParallelJobs((int) $input->getOption('parallel'));
        }

        return $config;
    }

    /**
     * Run the actual analysis.
     *
     * @param InputInterface $input Input.
     * @param OutputInterface $output Output.
     * @param Config $config The configuration object.
     */
    private function analyze(InputInterface $input, OutputInterface $output, Config $config): ResultAccumulator
    {
        $filesFinder = (new FileFinder($config->getFileExtensions(), $config->getFilesToIgnore()))
            ->getPhpFiles($config->getDirectoriesToScan());
        $accumulator = new ResultAccumulator($config->getFilesToShow(), $config->getMinScoreToShow());
        $this->processHandlerFactory->getProcessHandler($config)->process(
            $filesFinder,
            new ProcessFactory($config->getVCS(), $config->getCommitsSince()),
            $this->getOnSuccessObserver($input, $output, $accumulator)
        );

        return $accumulator;
    }

    /**
     * @param InputInterface $input Input.
     * @param OutputInterface $output Output.
     * @param ResultAccumulator $accumulator The object accumulating the results.
     */
    private function getOnSuccessObserver(
        InputInterface $input,
        OutputInterface $output,
        ResultAccumulator $accumulator
    ): OnSuccess {
        $observer = new OnSuccessAccumulate($accumulator);

        if ((bool) $input->getOption('progress')) {
            $progressBar = new ProgressBar($output);
            $progressBar->start();
            $observer = new OnSuccessCollection($observer, new OnSuccessProgress($progressBar));
        }

        return $observer;
    }

    /**
     * @param InputInterface $input Input.
     * @param OutputInterface $output Output.
     */
    private function displayLogo(InputInterface $input, OutputInterface $output): void
    {
        if ('text' !== $input->getOption('format') && empty($input->getOption('output'))) {
            return;
        }

        $output->writeln(self::LOGO);
    }

    /**
     * @param InputInterface $input Input.
     * @param OutputInterface $output Output.
     * @param ResultAccumulator $accumulator The results to write.
     */
    private function writeResult(InputInterface $input, OutputInterface $output, ResultAccumulator $accumulator): void
    {
        if ((bool) $input->getOption('progress')) {
            $output->writeln("\n");
        }

        if (!empty($input->getOption('output'))) {
            $output = new StreamOutput(
                \fopen((string) $input->getOption('output'), 'w+'),
                OutputInterface::VERBOSITY_NORMAL,
                false
            );
        }

        $renderer = $this->renderFactory->getRenderer($input->getOption('format'));
        $renderer->render($output, $accumulator->toArray());
    }
}
