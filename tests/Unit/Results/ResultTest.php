<?php declare(strict_types = 1);

namespace Churn\Tests\Results;

use Churn\Tests\BaseTestCase;
use Churn\Results\Result;

class ResultTest extends BaseTestCase
{
    /**
     * The object we're testing.
     * @var Result
     */
    protected $result;

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(Result::class, $this->result);
    }

    /** @test */
    public function it_can_return_the_file()
    {
        $this->assertSame('filename.php', $this->result->getFile());
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
    public function it_can_calculate_the_score()
    {
        $maxCommits = 10;
        $maxComplexity = 10;

        $this->assertEquals(0.41690481051547, $this->result->getScore($maxCommits, $maxComplexity));
    }

    /** @test */
    public function it_can_be_returned_as_an_array()
    {
        $this->assertSame([
                'filename.php',
                5,
                7
            ],
            $this->result->toArray()
        );
    }

    public function setup()
    {
        $this->result = new Result([
            'file'       => 'filename.php',
            'commits'    => 5,
            'complexity' => 7,
        ]);
    }
}