<?php declare(strict_types = 1);

namespace Churn\Processes;

use Churn\Configuration\Config;
use Churn\Processes\Handler\ParallelProcessHandler;
use Churn\Processes\Handler\ProcessHandler;
use Churn\Processes\Handler\SequentialProcessHandler;

class ProcessHandlerFactory
{
    /**
     * Returns a process handler depending on the configuration.
     * @param Config $config The application configuration.
     * @return ProcessHandler
     */
    public function getProcessHandler(Config $config): ProcessHandler
    {
        if ($config->getParallelJobs() > 1) {
            return new ParallelProcessHandler($config->getParallelJobs());
        }

        return new SequentialProcessHandler();
    }
}
