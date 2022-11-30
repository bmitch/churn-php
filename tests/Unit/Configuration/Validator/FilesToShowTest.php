<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\FilesToShow;

final class FilesToShowTest extends ValidatorBaseTestCase
{
    /** {@inheritDoc} */
    protected function getValidator(): Validator
    {
        return new FilesToShow();
    }

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    protected function getValue(Config $config)
    {
        return $config->getFilesToShow();
    }

    /** {@inheritDoc} */
    protected function getDefaultValue()
    {
        return 10;
    }

    /** {@inheritDoc} */
    public static function provideValidValues(): iterable
    {
        yield 'FilesToShow' => [13];
    }

    /** {@inheritDoc} */
    public static function provideInvalidValues(): iterable
    {
        yield 'FilesToShow / string' => ['foo', 'Files to show should be an integer'];
        yield 'FilesToShow / null' => [null, 'Files to show should be an integer'];
    }
}
