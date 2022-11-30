<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Assessor;

use Churn\Assessor\CyclomaticComplexityAssessor;
use Churn\Tests\BaseTestCase;

final class CyclomaticComplexityAssessorTest extends BaseTestCase
{
    private const CODE_EMPTY_CLASS = <<<'EOC'
<?php
class EmptyClass
{
}
EOC;

    private const CODE_ONE_METHOD_CLASS = <<<'EOC'
<?php
class ClassWithOneEmptyMethod
{
    public function foobar()
    {
    }
}
EOC;

    private const CODE_ONE_CONDITION_IN_ONE_METHOD = <<<'EOC'
<?php
class ClassWithOneMethodWithOneIf
{
    public function foobar()
    {
        if ($true) {
            return true;
        }

        return false;
    }
}
EOC;

    private const CODE_ONE_NESTED_CONDITION = <<<'EOC'
<?php
class ClassWithOneMethodWithNestedIf
{
    public function foobar()
    {
        if ($true) {
            if ($true) {
                return true;
            }
        }

        return false;
    }
}
EOC;

    private const CODE_WITH_ELSE_IF_STATEMENT = <<<'EOC'
<?php
class ClassWithIfElseIf
{
    public function foobar()
    {
        if ($true) {
            return true;
        } elseif ($false) {
            return false;
        }
    }
}
EOC;

    private const CODE_WITH_WHILE_LOOP = <<<'EOC'
<?php
class ClassWithWhileLoop
{
    public function foobar()
    {
        while ($c == $d) {
            foobar();
        }
    }
}
EOC;

    private const CODE_WITH_FOR_LOOP = <<<'EOC'
<?php
class ClassWithForLoop
{
    public function foobar()
    {
        for ($n = 0; $n < $h; $n++) {
            foobar();
        }
    }
}
EOC;

    private const CODE_WITH_SWITCH = <<<'EOC'
<?php
class ClassWithSwitch
{
    public function foobar()
    {
        switch ($z) {
            case 1:
                foobar();
                break;
            case 2:
                foobar();
                break;
            case 3:
                foobar();
                break;
            default:
                foobar();
                break;
        }
    }
}
EOC;

    private const CODE_LONG_CLASS = <<<'EOC'
<?php
class LongClass
{
    public function foo()
    {
        for ($n = 0; $n < $h; $n++) {
            foobar();
        }
    }

    public function bar()
    {
        if ($true) {
            return true;
        } elseif ($false) {
            return false;
        }

        if ($true) {
            if ($true) {
                return true;
            }
        }

        return false;
    }

    public function baz()
    {
        if ($true) {
            return true;
        }

        return false;
    }

    public function uggh()
    {
        switch ($z) {
            case 1:
                foobar();
                break;
            case 2:
                foobar();
                break;
            case 3:
                foobar();
                break;
            default:
                foobar();
                break;
        }

        while ($c == $d) {
            foobar();
        }
    }
}
EOC;

    private const CODE_WITH_TERNARY = <<<'EOC'
<?php
class ClassWithTernary
{
    public function foobar()
    {
        $foo == 'bar' ? $baz++ : $zug;
    }
}
EOC;

    private const CODE_WITH_LOGICAL_AND = <<<'EOC'
<?php
class ClassWithLogicalAnd
{
    public function foobar()
    {
        return ($a == $b && $c == $d);
    }
}
EOC;

    private const CODE_WITH_LOGICAL_OR = <<<'EOC'
<?php
class ClassWithLogicalOr
{
    public function foobar()
    {
        return ($a == $b || $c == $d);
    }
}
EOC;

    private const CODE_WITH_COALESCE_EQUAL = <<<'EOC'
<?php
$a ??= 'a';
$a ??= 'a';
$a ??= 'a';
EOC;

    /**
     * @dataProvider provide_assess
     * @param integer $expectedScore The expected score.
     * @param string $code Some PHP code.
     */
    public function test_assess(int $expectedScore, string $code): void
    {
        $assessor = new CyclomaticComplexityAssessor();

        self::assertSame($expectedScore, $assessor->assess($code));
    }

    /**
     * @return iterable<string, array{int, string}>
     */
    public static function provide_assess(): iterable
    {
        yield 'an empty file' => [1, ''];
        yield 'an empty class' => [1, self::CODE_EMPTY_CLASS];
        yield 'a class with one empty method' => [1, self::CODE_ONE_METHOD_CLASS];
        yield 'a class with a method containing one if statement' => [2, self::CODE_ONE_CONDITION_IN_ONE_METHOD];
        yield 'a class with a method containing a nested if statement' => [3, self::CODE_ONE_NESTED_CONDITION];
        yield 'a class with a method containing an else if statement' => [3, self::CODE_WITH_ELSE_IF_STATEMENT];
        yield 'a class with a method containing a while loop' => [2, self::CODE_WITH_WHILE_LOOP];
        yield 'a class with a method containing a for loop' => [2, self::CODE_WITH_FOR_LOOP];
        yield 'a class with a method a switch statement with 3 cases' => [4, self::CODE_WITH_SWITCH];
        yield 'this class with many methods and many branches' => [11, self::CODE_LONG_CLASS];
        yield 'a class with a ternary operator' => [2, self::CODE_WITH_TERNARY];
        yield 'a class with a logical AND' => [2, self::CODE_WITH_LOGICAL_AND];
        yield 'a class with a logical OR' => [2, self::CODE_WITH_LOGICAL_OR];
        yield 'syntax error' => [1, '<?php echo'];
        yield 'file with commented code' => [1, '<?php // if (true) {if (true) {if (true) {if (true) {}}}}'];

        if (!\version_compare(\PHP_VERSION, '7.4.0', '>=')) {
            return;
        }

        yield 'file with coalesce equal operator' => [3, self::CODE_WITH_COALESCE_EQUAL];
    }
}
