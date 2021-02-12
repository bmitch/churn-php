<?php

declare(strict_types=1);

namespace Churn\Process;

use Churn\Configuration\Config;
use Churn\Event\Broker;
use Churn\Process\Handler\ParallelProcessHandler;
use Churn\Process\Handler\ProcessHandler;
use Churn\Process\Handler\SequentialProcessHandler;

class ProcessHandlerFactory
{

    /**
     * Returns a process handler depending on the configuration.
     *
     * @param Config $config The application configuration.
     * @param Broker $broker The event broker.
     */
    public function getProcessHandler(Config $config, Broker $broker): ProcessHandler
    {
        if ($config->getParallelJobs() > 1) {
            return new ParallelProcessHandler($config->getParallelJobs(), $broker);
        }

        return new SequentialProcessHandler($broker);
    }
}
