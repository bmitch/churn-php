<?php

declare(strict_types=1);

namespace Churn\Process\Observer;

use Churn\Process\CacheProcessFactory;
use Churn\Result\ResultAccumulator;
use Symfony\Component\Console\Helper\ProgressBar;

class OnSuccessBuilder
{

    /**
     * @param array<mixed> $objects Objects who need to observe OnSuccess events.
     */
    public function build(array $objects): OnSuccess
    {
        $observers = [];

        foreach ($objects as $object) {
            $this->addOnSuccess($object, $observers);
            $this->addOnSuccessProgress($object, $observers);
            $this->addOnSuccessAccumulate($object, $observers);
            $this->addOnSuccessCache($object, $observers);
        }

        if (1 === \count($observers)) {
            return $observers[0];
        }

        return new OnSuccessCollection(...$observers);
    }

    /**
     * @param mixed $object Object who needs to observe OnSuccess events.
     * @param array<OnSuccess> $observers Collection of observers.
     */
    private function addOnSuccess($object, array &$observers): void
    {
        if (!$object instanceof OnSuccess) {
            return;
        }

        $observers[] = $object;
    }

    /**
     * @param mixed $object Object who needs to observe OnSuccess events.
     * @param array<OnSuccess> $observers Collection of observers.
     */
    private function addOnSuccessProgress($object, array &$observers): void
    {
        if (!$object instanceof ProgressBar) {
            return;
        }

        $observers[] = new OnSuccessProgress($object);
    }

    /**
     * @param mixed $object Object who needs to observe OnSuccess events.
     * @param array<OnSuccess> $observers Collection of observers.
     */
    private function addOnSuccessAccumulate($object, array &$observers): void
    {
        if (!$object instanceof ResultAccumulator) {
            return;
        }

        $observers[] = new OnSuccessAccumulate($object);
    }

    /**
     * @param mixed $object Object who needs to observe OnSuccess events.
     * @param array<OnSuccess> $observers Collection of observers.
     */
    private function addOnSuccessCache($object, array &$observers): void
    {
        if (!$object instanceof CacheProcessFactory) {
            return;
        }

        $observers[] = new OnSuccessCache($object);
    }
}
