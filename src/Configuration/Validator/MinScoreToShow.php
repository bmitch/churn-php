<?php

declare(strict_types=1);

namespace Churn\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\Validator;
use Webmozart\Assert\Assert;

/**
 * @internal
 */
final class MinScoreToShow implements Validator
{
    private const KEY = 'minScoreToShow';

    /**
     * Returns the configuration key.
     */
    public function getKey(): string
    {
        return self::KEY;
    }

    /**
     * @param Config $config The configuration object.
     * @param array<mixed> $configuration The array containing the configuration values.
     */
    public function validate(Config $config, array $configuration): void
    {
        if (!\array_key_exists(self::KEY, $configuration)) {
            return;
        }

        $value = $configuration[self::KEY];
        if (null === $value) {
            $config->setMinScoreToShow(null);

            return;
        }

        Assert::numeric($value, 'Minimum score to show should be a number');

        $config->setMinScoreToShow((float) $value);
    }
}
