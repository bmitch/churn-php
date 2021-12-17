<?php

declare(strict_types=1);

namespace Churn\Event\Subscriber;

use Churn\Event\Event\BeforeAnalysis as BeforeAnalysisEvent;

/**
 * @internal
 * @implements HookDecorator<\Churn\Event\Hook\BeforeAnalysisHook>
 */
class BeforeAnalysisHookDecorator implements BeforeAnalysis, HookDecorator
{
    /**
     * @var class-string<\Churn\Event\Hook\BeforeAnalysisHook>
     */
    private $hook;

    /**
     * @param string $hook The user-defined hook class name.
     * @psalm-param class-string<\Churn\Event\Hook\BeforeAnalysisHook> $hook
     */
    public function __construct(string $hook)
    {
        $this->hook = $hook;
    }

    /**
     * @param BeforeAnalysisEvent $event The event triggered when the analysis starts.
     */
    public function onBeforeAnalysis(BeforeAnalysisEvent $event): void
    {
        $this->hook::beforeAnalysis($event);
    }
}
