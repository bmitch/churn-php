<?php

declare(strict_types=1);

namespace Churn\Event\Subscriber;

use Churn\Event\Event\AfterFileAnalysis as AfterFileAnalysisEvent;

/**
 * @internal
 * @implements HookDecorator<\Churn\Event\Hook\AfterFileAnalysisHook>
 */
class AfterFileAnalysisHookDecorator implements AfterFileAnalysis, HookDecorator
{
    /**
     * @var class-string<\Churn\Event\Hook\AfterFileAnalysisHook>
     */
    private $hook;

    /**
     * @param string $hook The user-defined hook class name.
     * @psalm-param class-string<\Churn\Event\Hook\AfterFileAnalysisHook> $hook
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
        $this->hook::afterFileAnalysis($event);
    }
}
