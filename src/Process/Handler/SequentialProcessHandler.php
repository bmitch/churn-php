<?php

declare(strict_types=1);

namespace Churn\Process\Handler;

use Churn\Event\Broker;
use Churn\Event\Event\AfterFileAnalysisEvent;
use Churn\Process\ProcessFactory;
use Churn\Process\ProcessInterface;
use Churn\Result\Result;
use Generator;

/**
 * @internal
 */
class SequentialProcessHandler extends BaseProcessHandler
{
    /**
     * @var Broker
     */
    private $broker;

    /**
     * @param Broker $broker The event broker.
     */
    public function __construct(Broker $broker)
    {
        $this->broker = $broker;
    }

    /**
     * Run the processes sequentially to gather information.
     *
     * @param Generator $filesFinder Collection of files.
     * @param ProcessFactory $processFactory Process Factory.
     * @psalm-param Generator<\Churn\File\File> $filesFinder
     */
    public function process(Generator $filesFinder, ProcessFactory $processFactory): void
    {
        foreach ($filesFinder as $file) {
            $result = new Result($file);

            foreach ($processFactory->createProcesses($file) as $process) {
                $this->executeProcess($process, $result);
            }

            $this->broker->notify(new AfterFileAnalysisEvent($result));
        }
    }

    /**
     * Execute a process and save the result when it's done.
     *
     * @param ProcessInterface $process The process to execute.
     * @param Result $result The result to complete.
     */
    private function executeProcess(ProcessInterface $process, Result $result): void
    {
        $process->start();

        while (!$process->isSuccessful());

        $this->saveResult($process, $result);
    }
}
