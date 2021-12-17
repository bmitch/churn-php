<?php

declare(strict_types=1);

namespace Churn\Event\Subscriber;

use Churn\Event\Event\AfterAnalysis as AfterAnalysisEvent;

/**
 * @internal
 * @implements HookDecorator<\Churn\Event\Hook\AfterAnalysisHook>
 */
class AfterAnalysisHookDecorator implements AfterAnalysis, HookDecorator
{
    /**
     * @var class-string<\Churn\Event\Hook\AfterAnalysisHook>
     */
    private $hook;

    /**
     * @param string $hook The user-defined hook class name.
     * @psalm-param class-string<\Churn\Event\Hook\AfterAnalysisHook> $hook
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
        $this->hook::afterAnalysis($event);
    }
}
