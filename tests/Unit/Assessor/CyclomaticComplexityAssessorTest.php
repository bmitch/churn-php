<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Assessors\CyclomaticComplexity;

use Churn\Tests\BaseTestCase;
use Churn\Assessor\CyclomaticComplexityAssessor;

class CyclomaticComplexityAssessorTest extends BaseTestCase
{
    /** @test */
    public function an_empty_class_should_have_a_complexity_of_one()
    {
        $this->assertEquals(1, $this->assess('tests/Unit/Assessor/Assets/EmptyClass.inc'));
    }

    /** @test */
    public function a_class_with_one_empty_method_has_a_complexity_of_one()
    {
        $this->assertEquals(1, $this->assess('tests/Unit/Assessor/Assets/ClassWithOneEmptyMethod.inc'));
    }

    /** @test */
    public function a_class_with_a_method_containing_one_if_statement_has_a_complexity_of_two()
    {
        $this->assertEquals(2, $this->assess('tests/Unit/Assessor/Assets/ClassWithOneMethodWithOneIf.inc'));
    }

    /** @test */
    public function a_class_with_a_method_containing_a_nested_if_statement_has_a_complexity_of_three()
    {
        $this->assertEquals(3, $this->assess('tests/Unit/Assessor/Assets/ClassWithOneMethodWithNestedIf.inc'));
    }

    /** @test */
    public function a_class_with_a_method_containing_an_if_else_if_statement_has_a_complexity_of_three()
    {
        $this->assertEquals(3, $this->assess('tests/Unit/Assessor/Assets/ClassWithIfElseIf.inc'));
    }

    /** @test */
    public function a_class_with_a_method_containing_a_while_loop_has_a_complexity_of_two()
    {
        $this->assertEquals(2, $this->assess('tests/Unit/Assessor/Assets/ClassWithWhileLoop.inc'));
    }

    /** @test */
    public function a_class_with_a_method_containing_a_for_loop_has_a_complexity_of_two()
    {
        $this->assertEquals(2, $this->assess('tests/Unit/Assessor/Assets/ClassWithForLoop.inc'));
        $this->assertEquals(1, $this->assess('tests/Unit/Assessor/Assets/ClassWithNoForLoops.inc'));
    }

    /** @test */
    public function a_class_with_a_method_a_switch_statement_with_three_cases_has_a_complexity_of_four()
    {
        $this->assertEquals(4, $this->assess('tests/Unit/Assessor/Assets/ClassWithSwitchStatement.inc'));
    }

    /** @test */
    public function this_class_with_many_methods_and_many_branches_should_have_a_complexity_of_eleven()
    {
        $this->assertEquals(11, $this->assess('tests/Unit/Assessor/Assets/ClassWithManyMethodsAndLotsOfBranches.inc'));
    }

    /** @test */
    public function a_class_with_a_ternary_opererator_should_return_two()
    {
        $this->assertEquals(2, $this->assess('tests/Unit/Assessor/Assets/ClassWithTernary.inc'));
    }

    /** @test */
    public function a_class_with_a_logical_and_should_return_two()
    {
        $this->assertEquals(2, $this->assess('tests/Unit/Assessor/Assets/ClassWithLogicalAnd.inc'));
    }

    /** @test */
    public function a_class_with_a_logical_or_should_return_two()
    {
        $this->assertEquals(2, $this->assess('tests/Unit/Assessor/Assets/ClassWithLogicalOr.inc'));
    }

    protected function assess($filename)
    {
        $contents = \file_get_contents($filename);
        assert($contents !== false);

        return (new CyclomaticComplexityAssessor())->assess($contents);
    }
}
