<?php

declare(strict_types=1);

namespace Churn\Configuration;

/**
 * @internal
 */
interface Validator
{
    /**
     * Returns the configuration key.
     */
    public function getKey(): string;

    /**
     * @param EditableConfig $config The configuration object.
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    public function validate(EditableConfig $config, array $configuration): void;
}
