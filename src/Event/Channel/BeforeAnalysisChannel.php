<?php

declare(strict_types=1);

namespace Churn\Event\Channel;

use Churn\Event\Channel;
use Churn\Event\Event\BeforeAnalysis as BeforeAnalysisEvent;
use Churn\Event\Subscriber\BeforeAnalysis;
use Closure;

/**
 * @internal
 * @implements Channel<BeforeAnalysis, BeforeAnalysisEvent>
 */
final class BeforeAnalysisChannel implements Channel
{
    /**
     * @param object $subscriber A subscriber instance.
     */
    #[\Override]
    public function accepts($subscriber): bool
    {
        return $subscriber instanceof BeforeAnalysis;
    }

    /**
     * @psalm-return class-string<BeforeAnalysisEvent>
     */
    #[\Override]
    public function getEventClassname(): string
    {
        return BeforeAnalysisEvent::class;
    }

    /**
     * @param object $subscriber A subscriber instance.
     * @psalm-param BeforeAnalysis $subscriber
     * @psalm-return Closure(BeforeAnalysisEvent): void
     */
    #[\Override]
    public function buildEventHandler($subscriber): Closure
    {
        return static function (BeforeAnalysisEvent $event) use ($subscriber): void {
            $subscriber->onBeforeAnalysis($event);
        };
    }
}
