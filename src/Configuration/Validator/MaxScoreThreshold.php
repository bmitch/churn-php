<?php

declare(strict_types=1);

namespace Churn\Configuration\Validator;

use Churn\Configuration\EditableConfig;
use Webmozart\Assert\Assert;

/**
 * @internal
 */
final class MaxScoreThreshold extends BaseValidator
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct('maxScoreThreshold');
    }

    /**
     * @param EditableConfig $config The configuration object.
     * @param mixed $value The value to validate.
     */
    protected function validateValue(EditableConfig $config, $value): void
    {
        if (null === $value) {
            $config->setMaxScoreThreshold(null);

            return;
        }

        Assert::numeric($value, 'Maximum score threshold should be a number');

        $config->setMaxScoreThreshold((float) $value);
    }
}
