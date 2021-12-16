<?php

declare(strict_types=1);

namespace Churn\Configuration\Validator;

use Churn\Configuration\EditableConfig;
use Webmozart\Assert\Assert;

/**
 * @internal
 */
final class FileExtensions extends BaseValidator
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct('fileExtensions');
    }

    /**
     * @param EditableConfig $config The configuration object.
     * @param mixed $value The value to validate.
     */
    protected function validateValue(EditableConfig $config, $value): void
    {
        Assert::isArray($value, 'File extensions should be an array of strings');
        Assert::allString($value, 'File extensions should be an array of strings');

        $config->setFileExtensions($value);
    }
}
