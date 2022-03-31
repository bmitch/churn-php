<?php

declare(strict_types=1);

namespace Churn\Event;

/**
 * @internal
 */
interface Broker
{
    /**
     * @param object $subscriber A subscriber object.
     */
    public function subscribe($subscriber): void;

    /**
     * @param Event $event The triggered event.
     */
    public function notify(Event $event): void;
}
