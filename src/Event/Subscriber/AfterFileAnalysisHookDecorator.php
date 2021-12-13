<?php

declare(strict_types=1);

namespace Churn\Event\Subscriber;

use Churn\Event\Event\AfterFileAnalysisEvent;

/**
 * @internal
 */
class AfterFileAnalysisHookDecorator implements AfterFileAnalysis
{
    /**
     * @var string
     */
    private $hook;

    /**
     * @param string $hook The user-defined hook class name.
     */
    public function __construct(string $hook)
    {
        $this->hook = $hook;
    }

    /**
     * @param AfterFileAnalysisEvent $event The event triggered when the analysis of a file is done.
     */
    public function onAfterFileAnalysis(AfterFileAnalysisEvent $event): void
    {
        \call_user_func([$this->hook, 'afterFileAnalysis'], $event);
    }
}
