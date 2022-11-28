<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\Hooks;
use Churn\Tests\Unit\Configuration\ValidatorBaseTestCase;

final class HooksTest extends ValidatorBaseTestCase
{
    /** {@inheritDoc} */
    protected function getValidator(): Validator
    {
        return new Hooks();
    }

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    protected function getValue(Config $config)
    {
        return $config->getHooks();
    }

    /** {@inheritDoc} */
    protected function getDefaultValue()
    {
        return [];
    }

    /** {@inheritDoc} */
    public function provideValidValues(): iterable
    {
        yield 'Hook' => [['Hook1', 'Hook2']];
    }

    /** {@inheritDoc} */
    public function provideInvalidValues(): iterable
    {
        yield 'Hooks / string' => ['foo', 'Hooks should be an array of strings'];
        yield 'Hooks / null' => [null, 'Hooks should be an array of strings'];
    }
}
