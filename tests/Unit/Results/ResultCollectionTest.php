<?php declare(strict_types = 1);

namespace Churn\Tests\Assessors\CyclomaticComplexity;

use Churn\Tests\BaseTestCase;
use Churn\Results\Result;
use Churn\Results\ResultCollection;

class ResultCollectionTest extends BaseTestCase
{
    /**
     * The object we're testing.
     * @var ResultCollection
     */
    protected $resultCollection;

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(ResultCollection::class, $this->resultCollection);
    }

    /** @test */
    public function it_can_order_the_results_by_score_descending()
    {
        $this->assertSame(12, $this->resultCollection->first()->getScore());
        $this->assertSame(16, $this->resultCollection->orderByScoreDesc()->first()->getScore());
    }

    public function setup()
    {
        $this->resultCollection = new ResultCollection([
            new Result(['file' => 'filename.php', 'commits'    => 5, 'complexity' => 7]),
            new Result(['file' => 'filename2.php', 'commits'   => 6, 'complexity' => 8]),
            new Result(['file' => 'filename3.php', 'commits'   => 7, 'complexity' => 9]),
        ]);
    }
}