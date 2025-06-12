<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\EditableConfig;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\CachePath;

final class CachePathTest extends ValidatorBaseTestCase
{
    /** {@inheritDoc} */
    #[\Override]
    protected function getValidator(): Validator
    {
        return new CachePath();
    }

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    #[\Override]
    protected function getValue(Config $config)
    {
        return $config->getCachePath();
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function getDefaultValue()
    {
        return null;
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function provideValidValues(): iterable
    {
        yield 'CachePath' => ['/tmp/.churn.cache'];
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function provideInvalidValues(): iterable
    {
        yield 'CachePath / int' => [123, 'Cache path should be a string'];
    }

    /**
     * @test
     */
    public function it_accepts_null(): void
    {
        $config = new EditableConfig();
        // set non-null value to test it will be changed
        $config->setCachePath('/cache/path');
        $validator = $this->getValidator();

        $validator->validate($config, [$validator->getKey() => null]);

        self::assertNull($this->getValue($config));
    }
}
