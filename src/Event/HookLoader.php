<?php

declare(strict_types=1);

namespace Churn\Event;

use Churn\Event\Hook\AfterAnalysisHook;
use Churn\Event\Hook\AfterFileAnalysisHook;
use Churn\Event\Hook\BeforeAnalysisHook;
use Churn\Event\Subscriber\AfterAnalysisHookDecorator;
use Churn\Event\Subscriber\AfterFileAnalysisHookDecorator;
use Churn\Event\Subscriber\BeforeAnalysisHookDecorator;
use Churn\File\FileHelper;
use InvalidArgumentException;

/**
 * @internal
 */
final class HookLoader
{
    /**
     * @var array<string, string>
     * @psalm-var array<
     * class-string<\Churn\Event\Subscriber\HookDecorator>,
     * class-string<AfterAnalysisHook>|class-string<AfterFileAnalysisHook>|class-string<BeforeAnalysisHook>
     * >
     */
    private $decorators;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @param string $basePath The base path for the hooks files.
     */
    public function __construct(string $basePath)
    {
        $this->decorators = [
            AfterAnalysisHookDecorator::class => AfterAnalysisHook::class,
            AfterFileAnalysisHookDecorator::class => AfterFileAnalysisHook::class,
            BeforeAnalysisHookDecorator::class => BeforeAnalysisHook::class,
        ];
        $this->basePath = $basePath;
    }

    /**
     * @param array<string> $hooks The list of hooks to attach.
     * @param Broker $broker The event broker.
     * @throws InvalidArgumentException If a hook is invalid.
     */
    public function attachHooks(array $hooks, Broker $broker): void
    {
        if ([] === $hooks) {
            return;
        }

        foreach ($hooks as $hook) {
            if (!$this->attach($hook, $broker)) {
                throw new InvalidArgumentException('Invalid hook: ' . $hook);
            }
        }
    }

    /**
     * @param string $hookPath The class name or the file path of the hook.
     * @param Broker $broker The event broker.
     */
    public function attach(string $hookPath, Broker $broker): bool
    {
        $foundSubscriber = false;
        $subscribers = [];

        foreach ($this->loadHooks($hookPath) as $hookName) {
            $subscribers = \array_merge($subscribers, $this->hookToSubscribers($hookName));
        }

        foreach ($subscribers as $subscriber) {
            $broker->subscribe($subscriber);
            $foundSubscriber = true;
        }

        return $foundSubscriber;
    }

    /**
     * @param string $hookPath The class name or the file path of the hook.
     * @return array<string>
     * @psalm-return array<class-string>
     */
    private function loadHooks(string $hookPath): array
    {
        if (\class_exists($hookPath)) {
            return [$hookPath];
        }

        $hookPath = FileHelper::toAbsolutePath($hookPath, $this->basePath);

        if (!\is_file($hookPath)) {
            return [];
        }

        $numberOfClasses = \count(\get_declared_classes());
        \Churn\Event\includeOnce($hookPath);

        return \array_slice(\get_declared_classes(), $numberOfClasses);
    }

    /**
     * @param string $hookName The class name of the hook.
     * @return array<\Churn\Event\Subscriber\HookDecorator>
     * @psalm-param class-string $hookName
     */
    private function hookToSubscribers(string $hookName): array
    {
        $subscribers = [];

        foreach ($this->decorators as $decorator => $hook) {
            if (!\is_subclass_of($hookName, $hook)) {
                continue;
            }

            $subscribers[] = new $decorator($hookName);
        }

        return $subscribers;
    }
}

/**
 * Scope isolated include.
 *
 * Prevents access to $this/self from included files.
 *
 * @param string $file The PHP file to include.
 */
function includeOnce(string $file): void
{
    include_once $file;
}
