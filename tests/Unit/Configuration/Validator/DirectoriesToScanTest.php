<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\DirectoriesToScan;

final class DirectoriesToScanTest extends ValidatorBaseTestCase
{
    /** {@inheritDoc} */
    #[\Override]
    protected function getValidator(): Validator
    {
        return new DirectoriesToScan();
    }

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    #[\Override]
    protected function getValue(Config $config)
    {
        return $config->getDirectoriesToScan();
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function getDefaultValue()
    {
        return [];
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function provideValidValues(): iterable
    {
        yield 'DirectoriesToScan' => [['src', 'tests']];
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function provideInvalidValues(): iterable
    {
        yield 'DirectoriesToScan / string' => ['foo', 'Directories to scan should be an array of strings'];
        yield 'DirectoriesToScan / null' => [null, 'Directories to scan should be an array of strings'];
    }
}
