<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\Vcs;

final class VcsTest extends ValidatorBaseTestCase
{
    /** {@inheritDoc} */
    protected function getValidator(): Validator
    {
        return new Vcs();
    }

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    protected function getValue(Config $config)
    {
        return $config->getVCS();
    }

    /** {@inheritDoc} */
    protected function getDefaultValue()
    {
        return 'git';
    }

    /** {@inheritDoc} */
    public static function provideValidValues(): iterable
    {
        yield 'Vcs' => ['none'];
    }

    /** {@inheritDoc} */
    public static function provideInvalidValues(): iterable
    {
        yield 'Vcs / int' => [123, 'VCS should be a string'];
        yield 'Vcs / null' => [null, 'VCS should be a string'];
    }
}
