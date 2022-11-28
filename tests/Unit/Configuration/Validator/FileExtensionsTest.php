<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\FileExtensions;
use Churn\Tests\Unit\Configuration\ValidatorBaseTestCase;

final class FileExtensionsTest extends ValidatorBaseTestCase
{
    /** {@inheritDoc} */
    protected function getValidator(): Validator
    {
        return new FileExtensions();
    }

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    protected function getValue(Config $config)
    {
        return $config->getFileExtensions();
    }

    /** {@inheritDoc} */
    protected function getDefaultValue()
    {
        return ['php'];
    }

    /** {@inheritDoc} */
    public function provideValidValues(): iterable
    {
        yield 'FileExtensions' => [['php', 'inc']];
    }

    /** {@inheritDoc} */
    public function provideInvalidValues(): iterable
    {
        yield 'FileExtensions / string' => ['foo', 'File extensions should be an array of strings'];
        yield 'FileExtensions / null' => [null, 'File extensions should be an array of strings'];
    }
}
