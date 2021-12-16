<?php

declare(strict_types=1);

namespace Churn\Configuration\Validator;

use Churn\Configuration\EditableConfig;
use Webmozart\Assert\Assert;

/**
 * @internal
 */
final class MinScoreToShow extends BaseValidator
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct('minScoreToShow');
    }

    /**
     * @param EditableConfig $config The configuration object.
     * @param mixed $value The value to validate.
     */
    protected function validateValue(EditableConfig $config, $value): void
    {
        if (null === $value) {
            $config->setMinScoreToShow(null);

            return;
        }

        Assert::numeric($value, 'Minimum score to show should be a number');

        $config->setMinScoreToShow((float) $value);
    }
}
