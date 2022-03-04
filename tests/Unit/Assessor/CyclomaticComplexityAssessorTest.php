<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Assessors\CyclomaticComplexity;

use Churn\Tests\BaseTestCase;
use Churn\Assessor\CyclomaticComplexityAssessor;

class CyclomaticComplexityAssessorTest extends BaseTestCase
{
    /**
     * @dataProvider provide_assess
     */
    public function test_assess(int $expectedScore, string $code)
    {
        $assessor = new CyclomaticComplexityAssessor();

        $this->assertEquals($expectedScore, $assessor->assess($code));
    }

    public function provide_assess(): iterable
    {
        yield 'an empty file' => [
            1,
            ''
        ];

        yield 'an empty class' => [
            1,
            <<<'EOC'
<?php
class EmptyClass
{
}
EOC
        ];

        yield 'a class with one empty method' => [
            1,
            <<<'EOC'
<?php
class ClassWithOneEmptyMethod
{
    public function foobar()
    {
    }
}
EOC
        ];

        yield 'a class with a method containing one if statement' => [
            2,
            <<<'EOC'
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
EOC
        ];

        yield 'a class with a method containing a nested if statement' => [
            3,
            <<<'EOC'
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
EOC
        ];

        yield 'a class with a method containing an if else if statement' => [
            3,
            <<<'EOC'
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
EOC
        ];

        yield 'a class with a method containing a while loop' => [
            2,
            <<<'EOC'
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
EOC
        ];

        yield 'a class with a method containing a for loop' => [
            2,
            <<<'EOC'
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
EOC
        ];

        yield 'a class with a method a switch statement with 3 cases' => [
            4,
            <<<'EOC'
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
EOC
        ];

        yield 'this class with many methods and many branches' => [
            11,
            <<<'EOC'
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
EOC
        ];

        yield 'a class with a ternary operator' => [
            2,
            <<<'EOC'
<?php
class ClassWithTernary
{
    public function foobar()
    {
        $foo == 'bar' ? $baz++ : $zug;
    }
}
EOC
        ];

        yield 'a class with a logical AND' => [
            2,
            <<<'EOC'
<?php
class ClassWithLogicalAnd
{
    public function foobar()
    {
        return ($a == $b && $c == $d);
    }
}
EOC
        ];

        yield 'a class with a logical OR' => [
            2,
            <<<'EOC'
<?php
class ClassWithLogicalOr
{
    public function foobar()
    {
        return ($a == $b || $c == $d);
    }
}
EOC
        ];

        yield 'syntax error' => [
            1,
            '<?php echo'
        ];

        yield 'file with commented code' => [
            1,
            '<?php // if (true) {if (true) {if (true) {if (true) {}}}}'
        ];

        if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
            yield 'file with coalesce equal operator' => [
                3,
                <<<'EOC'
<?php
$a ??= 'a';
$a ??= 'a';
$a ??= 'a';
EOC
            ];
        }
    }
}
