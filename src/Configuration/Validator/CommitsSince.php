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
        parent::__construct('commitSince');
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
}
