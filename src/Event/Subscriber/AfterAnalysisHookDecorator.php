<?php

declare(strict_types=1);

namespace Churn\Event\Subscriber;

use Churn\Event\Event\AfterAnalysisEvent;

/**
 * @internal
 */
class AfterAnalysisHookDecorator implements AfterAnalysis
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
     * @param AfterAnalysisEvent $event The event triggered when the analysis is done.
     */
    public function onAfterAnalysis(AfterAnalysisEvent $event): void
    {
        \call_user_func([$this->hook, 'afterAnalysis'], $event);
    }
}
