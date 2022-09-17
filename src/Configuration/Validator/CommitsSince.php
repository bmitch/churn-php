<?php

declare(strict_types=1);

namespace Churn\Configuration\Validator;

use Churn\Configuration\EditableConfig;
use Webmozart\Assert\Assert;

/**
 * @internal
 */
final class CommitsSince extends BaseValidator
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct('commitsSince');
    }

    /**
     * @param EditableConfig $config The configuration object.
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    public function validate(EditableConfig $config, array $configuration): void
    {
        if (!$this->hasConfigurationKey($configuration)) {
            return;
        }

        /** @var mixed $value */
        $value = $configuration[$this->key];

        $this->validateValue($config, $value);
    }

    /**
     * @param EditableConfig $config The configuration object.
     * @param mixed $value The value to validate.
     */
    protected function validateValue(EditableConfig $config, $value): void
    {
        Assert::string($value, 'Commits since should be a string');

        $config->setCommitsSince($value);
    }

    /**
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    private function hasConfigurationKey(array $configuration): bool
    {
        if (\array_key_exists($this->key, $configuration)) {
            return true;
        }

        if (\array_key_exists('commitSince', $configuration)) {
            $this->key = 'commitSince';
            \trigger_error(
                'The "commitSince" configuration key is deprecated and won\'t be supported'
                . ' in the next major version anymore. Use "commitsSince" instead.',
                \E_USER_DEPRECATED
            );

            return true;
        }

        return false;
    }
}
