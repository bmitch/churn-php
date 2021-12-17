<?php

declare(strict_types=1);

namespace Churn\Event\Subscriber;

/**
 * @internal
 * @template H
 */
interface HookDecorator
{
    /**
     * @param string $hook The user-defined hook class name.
     * @psalm-param class-string<H> $hook
     */
    public function __construct(string $hook);
}
