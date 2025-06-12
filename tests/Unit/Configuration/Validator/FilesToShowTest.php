<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\FilesToShow;

final class FilesToShowTest extends ValidatorBaseTestCase
{
    /** {@inheritDoc} */
    #[\Override]
    protected function getValidator(): Validator
    {
        return new FilesToShow();
    }

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    #[\Override]
    protected function getValue(Config $config)
    {
        return $config->getFilesToShow();
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function getDefaultValue()
    {
        return 10;
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function provideValidValues(): iterable
    {
        yield 'FilesToShow' => [13];
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function provideInvalidValues(): iterable
    {
        yield 'FilesToShow / string' => ['foo', 'Files to show should be an integer'];
        yield 'FilesToShow / null' => [null, 'Files to show should be an integer'];
    }
}
