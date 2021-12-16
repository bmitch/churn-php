<?php

declare(strict_types=1);

namespace Churn\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\Validator;
use Webmozart\Assert\Assert;

/**
 * @internal
 */
final class ParallelJobs implements Validator
{
    private const KEY = 'parallelJobs';

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

        Assert::integer($value, 'Amount of parallel jobs should be an integer');

        $config->setParallelJobs($value);
    }
}
