<?php

declare(strict_types=1);

namespace Churn\Event;

use Closure;

/**
 * @internal
 * @template S of object
 * @template E of Event
 */
interface Channel
{
    /**
     * @param object $subscriber A subscriber instance.
     */
    public function accepts($subscriber): bool;

    /**
     * @return class-string<E>
     */
    public function getEventClassname(): string;

    /**
     * @param object $subscriber A subscriber instance.
     * @return Closure(E): void
     * @psalm-param S $subscriber
     */
    public function buildEventHandler($subscriber): Closure;
}
