<?php declare(strict_types = 1);

namespace Churn\Tests\Results;

use Churn\Configuration\Config;
use Churn\Results\Result;
use Churn\Results\ResultCollection;
use Churn\Tests\BaseTestCase;

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

    /** @test */
    public function it_can_convert_to_array()
    {
        $this->assertEquals(gettype($this->resultCollection->toArray()), 'array');
    }

    /** @test */
    public function it_can_convert_to_array_recursively()
    {
        $this->assertEquals(gettype($this->resultCollection->toArray()[0]), 'array');
    }

    /** @test */
    public function it_can_normalize_and_return_a_result_collection()
    {
        $config = Config::createFromDefaultValues();
        $results = $this->resultCollection->normalizeAgainst($config);

        $this->assertInstanceOf(ResultCollection::class, $results);
    }

    /** @test */
    public function it_can_normalize_against_no_custom_config()
    {
        $config = Config::createFromDefaultValues();
        $resultsArray = $this->resultCollection->normalizeAgainst($config)->toArray();

        $this->assertSame(5, count($resultsArray));
        $this->assertSame(['filename3.php', 7, 9, 16], $resultsArray[0]);
        $this->assertSame(['filename2.php', 6, 8, 14], $resultsArray[1]);
        $this->assertSame(['filename1.php', 5, 7, 12], $resultsArray[2]);
        $this->assertSame(['filename5.php', 0, 1, 1], $resultsArray[3]);
        $this->assertSame(['filename4.php', 0, 0, 0], $resultsArray[4]);
    }

    /** @test */
    public function it_can_normalize_against_custom_file_number()
    {
        $config = Config::create(['filesToShow' => 2]);
        $resultsArray = $this->resultCollection->normalizeAgainst($config)->toArray();

        $this->assertSame(2, count($resultsArray));
        $this->assertSame(['filename3.php', 7, 9, 16], $resultsArray[0]);
        $this->assertSame(['filename2.php', 6, 8, 14], $resultsArray[1]);
    }

    /** @test */
    public function it_can_normalize_against_custom_min_score()
    {
        $config = Config::create(['minScoreToShow' => 15]);
        $resultsArray = $this->resultCollection->normalizeAgainst($config)->toArray();

        $this->assertSame(1, count($resultsArray));
        $this->assertSame(['filename3.php', 7, 9, 16], $resultsArray[0]);
    }

    /** @test */
    public function it_can_normalize_against_custom_file_number_and_min_score_1()
    {
        $config = Config::create([
            'filesToShow' => 3,
            'minScoreToShow' => 1,
        ]);
        $resultsArray = $this->resultCollection->normalizeAgainst($config)->toArray();

        $this->assertSame(3, count($resultsArray));
        $this->assertSame(['filename3.php', 7, 9, 16], $resultsArray[0]);
        $this->assertSame(['filename2.php', 6, 8, 14], $resultsArray[1]);
        $this->assertSame(['filename1.php', 5, 7, 12], $resultsArray[2]);
    }

    /** @test */
    public function it_can_normalize_against_custom_file_number_and_min_score_2()
    {
        $config = Config::create([
            'filesToShow' => 4,
            'minScoreToShow' => 13,
        ]);
        $resultsArray = $this->resultCollection->normalizeAgainst($config)->toArray();

        $this->assertSame(2, count($resultsArray));
        $this->assertSame(['filename3.php', 7, 9, 16], $resultsArray[0]);
        $this->assertSame(['filename2.php', 6, 8, 14], $resultsArray[1]);
    }

    public function setup()
    {
        $this->resultCollection = new ResultCollection([
            new Result(['file' => 'filename1.php', 'commits' => 5, 'complexity' => 7]),
            new Result(['file' => 'filename2.php', 'commits' => 6, 'complexity' => 8]),
            new Result(['file' => 'filename3.php', 'commits' => 7, 'complexity' => 9]),
            new Result(['file' => 'filename4.php', 'commits' => 0, 'complexity' => 0]),
            new Result(['file' => 'filename5.php', 'commits' => 0, 'complexity' => 1]),
        ]);
    }
}