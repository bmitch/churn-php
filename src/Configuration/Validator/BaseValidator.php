<?php

declare(strict_types=1);

namespace Churn\Configuration\Validator;

use Churn\Configuration\EditableConfig;
use Churn\Configuration\Validator;

/**
 * @internal
 */
abstract class BaseValidator implements Validator
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @param EditableConfig $config The configuration object.
     * @param mixed $value The value to validate.
     */
    abstract protected function validateValue(EditableConfig $config, $value): void;

    /**
     * @param string $key The key of the configuration to validate.
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Returns the configuration key.
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param EditableConfig $config The configuration object.
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    public function validate(EditableConfig $config, array $configuration): void
    {
        if (!\array_key_exists($this->key, $configuration)) {
            return;
        }

        $value = $configuration[$this->key];

        $this->validateValue($config, $value);
    }
}
