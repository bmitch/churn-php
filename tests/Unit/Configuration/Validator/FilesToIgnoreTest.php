<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\FilesToIgnore;
use Churn\Tests\Unit\Configuration\ValidatorBaseTestCase;

final class FilesToIgnoreTest extends ValidatorBaseTestCase
{
    /** {@inheritDoc} */
    protected function getValidator(): Validator
    {
        return new FilesToIgnore();
    }

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    protected function getValue(Config $config)
    {
        return $config->getFilesToIgnore();
    }

    /** {@inheritDoc} */
    protected function getDefaultValue()
    {
        return [];
    }

    /** {@inheritDoc} */
    public function provideValidValues(): iterable
    {
        yield 'FilesToIgnore' => [['foo.php', 'bar.php', 'baz.php']];
    }

    /** {@inheritDoc} */
    public function provideInvalidValues(): iterable
    {
        yield 'FilesToIgnore / string' => ['foo', 'Files to ignore should be an array of strings'];
        yield 'FilesToIgnore / null' => [null, 'Files to ignore should be an array of strings'];
    }
}
