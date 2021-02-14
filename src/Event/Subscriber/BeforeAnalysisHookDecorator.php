<?php

declare(strict_types=1);

namespace Churn\Event\Subscriber;

use Churn\Event\Event\BeforeAnalysisEvent;

/**
 * @internal
 */
class BeforeAnalysisHookDecorator implements BeforeAnalysis
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
     * @param BeforeAnalysisEvent $event The event triggered when the analysis starts.
     */
    public function onBeforeAnalysis(BeforeAnalysisEvent $event): void
    {
        \call_user_func([$this->hook, 'beforeAnalysis'], $event);
    }
}
