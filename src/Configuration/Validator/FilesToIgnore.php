<?php

declare(strict_types=1);

namespace Churn\Configuration\Validator;

use Churn\Configuration\EditableConfig;
use Webmozart\Assert\Assert;

/**
 * @internal
 */
final class FilesToIgnore extends BaseValidator
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct('filesToIgnore');
    }

    /**
     * @param EditableConfig $config The configuration object.
     * @param mixed $value The value to validate.
     */
    protected function validateValue(EditableConfig $config, $value): void
    {
        Assert::isArray($value, 'Files to ignore should be an array of strings');
        Assert::allString($value, 'Files to ignore should be an array of strings');

        $config->setFilesToIgnore($value);
    }
}
