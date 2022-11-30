<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\EditableConfig;
use Churn\Configuration\Validator;
use Churn\Tests\BaseTestCase;
use InvalidArgumentException;

abstract class ValidatorBaseTestCase extends BaseTestCase
{
    /**
     * @var EditableConfig
     */
    protected $config;

    /**
     * @return Validator The instance to test.
     */
    abstract protected function getValidator(): Validator;

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    abstract protected function getValue(Config $config);

    /**
     * @return mixed
     */
    abstract protected function getDefaultValue();

    /**
     * @return iterable<string, array{mixed}>
     */
    abstract public static function provideValidValues(): iterable;

    /**
     * @return iterable<string, array{mixed, string}>
     */
    abstract public static function provideInvalidValues(): iterable;

    /** @return void */
    public function setUp()
    {
        parent::setUp();

        $this->config = new EditableConfig();
    }

        /**
     * @test
     */
    public function it_returns_the_default_value(): void
    {
        $validator = $this->getValidator();

        $validator->validate($this->config, []);

        self::assertSame($this->getDefaultValue(), $this->getValue($this->config));
    }

    /**
     * @test
     * @dataProvider provideValidValues
     * @param mixed $value The expected value.
     */
    public function it_returns_the_given_value($value): void
    {
        $validator = $this->getValidator();

        $validator->validate($this->config, [$validator->getKey() => $value]);

        self::assertSame($value, $this->getValue($this->config));
    }

    /**
     * @test
     * @dataProvider provideInvalidValues
     * @param mixed $invalidValue An invalid value to trigger an error.
     * @param string $errorMessage The expected error message.
     */
    public function it_throws_with_invalid_value($invalidValue, string $errorMessage): void
    {
        $validator = $this->getValidator();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($errorMessage);

        $validator->validate($this->config, [$validator->getKey() => $invalidValue]);
    }
}
