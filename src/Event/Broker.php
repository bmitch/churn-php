<?php

declare(strict_types=1);

namespace Churn\Event;

use Churn\Event\Channel\AfterAnalysisChannel;
use Churn\Event\Channel\AfterFileAnalysisChannel;
use Churn\Event\Channel\BeforeAnalysisChannel;

/**
 * @internal
 */
class Broker
{
    /**
     * @var array<class-string, array<callable>>
     */
    private $subscribers = [];

    /**
     * @var array<\Churn\Event\Channel>
     */
    private $channels;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->channels = [
            new AfterAnalysisChannel(),
            new AfterFileAnalysisChannel(),
            new BeforeAnalysisChannel(),
        ];
    }

    /**
     * @param object $subscriber A subscriber object.
     */
    public function subscribe($subscriber): void
    {
        foreach ($this->channels as $channel) {
            if (!$channel->accepts($subscriber)) {
                continue;
            }

            $this->subscribers[$channel->getEventClassname()][] = $channel->buildEventHandler($subscriber);
        }
    }

    /**
     * @param Event $event The triggered event.
     */
    public function notify(Event $event): void
    {
        foreach ($this->subscribers as $eventClass => $subscribers) {
            if (!$event instanceof $eventClass) {
                continue;
            }

            $this->notifyAll($event, $subscribers);
        }
    }

    /**
     * @param Event $event The triggered event.
     * @param array<callable> $subscribers The subscribers to notify.
     */
    private function notifyAll(Event $event, array $subscribers): void
    {
        foreach ($subscribers as $subscriber) {
            $subscriber($event);
        }
    }
}
