<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Result;

use Churn\File\File;
use Churn\Result\Result;
use Churn\Tests\BaseTestCase;
use InvalidArgumentException;

class ResultTest extends BaseTestCase
{
    /**
     * The object we're testing.
     * @var Result
     */
    protected $result;

    public function setup()
    {
        $this->result = new Result(new File('/filename.php', 'filename.php'));
        $this->result->setCommits(5);
        $this->result->setComplexity(7);
    }

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(Result::class, $this->result);
    }

    /** @test */
    public function it_can_return_the_file()
    {
        $this->assertSame('filename.php', $this->result->getFile()->getDisplayPath());
    }

    /** @test */
    public function it_is_complete()
    {
        $this->assertTrue($this->result->isComplete());
    }

    /** @test */
    public function it_can_return_the_commits()
    {
        $this->assertSame(5, $this->result->getCommits());
    }

    /** @test */
    public function it_can_return_the_complexity()
    {
        $this->assertSame(7, $this->result->getComplexity());
    }

    /** @test */
    public function it_can_return_the_priority()
    {
        $this->assertSame(5 * 7, $this->result->getPriority());
    }

    /** @test */
    public function it_can_calculate_the_score()
    {
        $maxCommits = 10;
        $maxComplexity = 10;

        $this->assertEquals(0.417, $this->result->getScore($maxCommits, $maxComplexity));
    }

    /**
     * @test
     * @dataProvider provide_uncomplete_result
     */
    public function it_returns_false_when_uncomplete(Result $result)
    {
        $this->assertFalse($result->isComplete());
    }

    public function provide_uncomplete_result(): iterable
    {
        $file = new File('/filename.php', 'filename.php');
        $result = new Result($file);
        yield [$result];

        $result = new Result($file);
        $result->setCommits(42);
        yield [$result];

        $result = new Result($file);
        $result->setComplexity(100);
        yield [$result];
    }

    /**
     * @test
     * @dataProvider provide_invalid_score
     */
    public function it_throws_when_score_is_invalid(int $maxCommits, int $maxComplexity)
    {
        $this->expectException(InvalidArgumentException::class);

        $this->result->getScore($maxCommits, $maxComplexity);
    }

    public function provide_invalid_score(): iterable
    {
        yield [0, 0];
        yield [0, 1];
        yield [1, 0];
    }
}
