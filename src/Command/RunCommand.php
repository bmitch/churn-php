<?php

declare(strict_types=1);

namespace Churn\Command;

use Churn\Command\Helper\MaxScoreChecker;
use Churn\Command\Helper\ProgressBarSubscriber;
use Churn\Configuration\Config;
use Churn\Configuration\Loader;
use Churn\Event\Broker;
use Churn\Event\Event\AfterAnalysisEvent;
use Churn\Event\Event\BeforeAnalysisEvent;
use Churn\Event\HookLoader;
use Churn\File\FileFinder;
use Churn\File\FileHelper;
use Churn\Process\CacheProcessFactory;
use Churn\Process\ConcreteProcessFactory;
use Churn\Process\ProcessFactory;
use Churn\Process\ProcessHandlerFactory;
use Churn\Result\ResultAccumulator;
use Churn\Result\ResultsRendererFactory;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Webmozart\Assert\Assert;

/**
 * @internal
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
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
            ->addOption('quiet', 'q', InputOption::VALUE_NONE, 'Suppress all normal output')
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
        if (true === $input->getOption('quiet')) {
            $output = new NullOutput();
        }
        $this->printLogo($input, $output);
        $config = $this->getConfiguration($input, $output);
        $broker = new Broker();
        (new HookLoader($config->getDirPath()))->attachHooks($config->getHooks(), $broker);
        if (true === $input->getOption('progress')) {
            $broker->subscribe(new ProgressBarSubscriber($output));
        }
        $report = $this->analyze($input, $config, $broker);
        $this->writeResult($input, $output, $report);

        return (int) (new MaxScoreChecker($config->getMaxScoreThreshold()))->isOverThreshold($input, $output, $report);
    }

    /**
     * @param InputInterface $input Input.
     * @param OutputInterface $output Output.
     */
    private function getConfiguration(InputInterface $input, OutputInterface $output): Config
    {
        $isDefaultValue = !$input->hasParameterOption('--configuration') && !$input->hasParameterOption('-c');
        $config = Loader::fromPath((string) $input->getOption('configuration'), $isDefaultValue);
        if ([] !== $input->getArgument('paths')) {
            $config->setDirectoriesToScan((array) $input->getArgument('paths'));
        }

        $this->checkConfiguration($config, $output);

        if (null !== $input->getOption('parallel')) {
            Assert::integerish($input->getOption('parallel'), 'Amount of parallel jobs should be an integer');
            $config->setParallelJobs((int) $input->getOption('parallel'));
        }

        return $config;
    }

    /**
     * @param Config $config The configuration object.
     * @param OutputInterface $output Output.
     * @throws InvalidArgumentException If paths argument invalid.
     */
    private function checkConfiguration(Config $config, OutputInterface $output): void
    {
        $unrecognizedKeys = $config->getUnrecognizedKeys();
        if ([] !== $unrecognizedKeys) {
            $output = $output instanceof ConsoleOutputInterface
                ? $output->getErrorOutput()
                : $output;
            $output->writeln('<error>Unrecognized configuration keys: ' . \implode(', ', $unrecognizedKeys) . "</>\n");
        }

        if ([] === $config->getDirectoriesToScan()) {
            throw new InvalidArgumentException(
                'Provide the directories you want to scan as arguments, ' .
                'or configure them under "directoriesToScan" in your churn.yml file.'
            );
        }
    }

    /**
     * Run the actual analysis.
     *
     * @param InputInterface $input Input.
     * @param Config $config The configuration object.
     * @param Broker $broker The event broker.
     */
    private function analyze(InputInterface $input, Config $config, Broker $broker): ResultAccumulator
    {
        $broker->subscribe($report = new ResultAccumulator($config->getFilesToShow(), $config->getMinScoreToShow()));
        $broker->subscribe($processFactory = $this->getProcessFactory($config));
        $broker->notify(new BeforeAnalysisEvent());
        $basePath = $this->getBasePath($input, $config);
        $filesFinder = (new FileFinder($config->getFileExtensions(), $config->getFilesToIgnore(), $basePath))
            ->getPhpFiles($config->getDirectoriesToScan());
        $this->processHandlerFactory->getProcessHandler($config, $broker)->process($filesFinder, $processFactory);
        $broker->notify(new AfterAnalysisEvent($report));

        return $report;
    }

    /**
     * @param InputInterface $input Input.
     * @param Config $config The configuration object.
     * @return string The base path.
     */
    private function getBasePath(InputInterface $input, Config $config): string
    {
        return [] === $input->getArgument('paths')
            ? $config->getDirPath()
            : \getcwd();
    }

    /**
     * @param Config $config The configuration object.
     */
    private function getProcessFactory(Config $config): ProcessFactory
    {
        $factory = new ConcreteProcessFactory($config->getVCS(), $config->getCommitsSince());

        $path = $config->getCachePath();
        if (null !== $path) {
            $basePath = $config->getDirPath();
            $factory = new CacheProcessFactory(FileHelper::toAbsolutePath($path, $basePath), $factory);
        }

        return $factory;
    }

    /**
     * @param InputInterface $input Input.
     * @param OutputInterface $output Output.
     */
    private function printLogo(InputInterface $input, OutputInterface $output): void
    {
        if ('text' !== $input->getOption('format') && empty($input->getOption('output'))) {
            return;
        }

        $output->writeln(self::LOGO);
    }

    /**
     * @param InputInterface $input Input.
     * @param OutputInterface $output Output.
     * @param ResultAccumulator $report The report to write.
     */
    private function writeResult(InputInterface $input, OutputInterface $output, ResultAccumulator $report): void
    {
        if (true === $input->getOption('progress')) {
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
        $renderer->render($output, $report->toArray());
    }
}
