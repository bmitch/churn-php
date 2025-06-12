<?php

declare(strict_types=1);

namespace Churn\Event\Channel;

use Churn\Event\Channel;
use Churn\Event\Event\AfterFileAnalysis as AfterFileAnalysisEvent;
use Churn\Event\Subscriber\AfterFileAnalysis;
use Closure;

/**
 * @internal
 * @implements Channel<AfterFileAnalysis, AfterFileAnalysisEvent>
 */
final class AfterFileAnalysisChannel implements Channel
{
    /**
     * @param object $subscriber A subscriber instance.
     */
    #[\Override]
    public function accepts($subscriber): bool
    {
        return $subscriber instanceof AfterFileAnalysis;
    }

    /**
     * @psalm-return class-string<AfterFileAnalysisEvent>
     */
    #[\Override]
    public function getEventClassname(): string
    {
        return AfterFileAnalysisEvent::class;
    }

    /**
     * @param object $subscriber A subscriber instance.
     * @psalm-param AfterFileAnalysis $subscriber
     * @psalm-return Closure(AfterFileAnalysisEvent): void
     */
    #[\Override]
    public function buildEventHandler($subscriber): Closure
    {
        return static function (AfterFileAnalysisEvent $event) use ($subscriber): void {
            $subscriber->onAfterFileAnalysis($event);
        };
    }
}
