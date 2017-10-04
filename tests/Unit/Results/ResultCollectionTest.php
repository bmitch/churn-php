<?php declare(strict_types=1);

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

    public function setup()
    {
        $this->resultCollection = new ResultCollection([
            // many commits, high complexity, score: 0.625
            new Result(['file' => 'filename1.php', 'commits' => 5, 'complexity' => 7]),

            // average commits, average complexity, score: 0.24217517235989
            new Result(['file' => 'filename2.php', 'commits' => 3, 'complexity' => 4]),

            // few commits, high complexity, score: 0.079534002224295
            new Result(['file' => 'filename3.php', 'commits' => 1, 'complexity' => 5]),

            // few commits, low complexity, score: -0.22487504568875
            new Result(['file' => 'filename4.php', 'commits' => 1, 'complexity' => 1]),

            // many commits, low complexity, score: 0.14285714285714
            new Result(['file' => 'filename5.php', 'commits' => 8, 'complexity' => 1]),
        ]);
    }

    /** @test */
    public function it_can_order_the_results_by_score_descending()
    {
        $this->assertResultsAre([
            'filename1.php',
            'filename2.php',
            'filename5.php',
            'filename3.php',
            'filename4.php',
        ], $this->resultCollection->orderByScoreDesc());
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
        $results = $this->resultCollection->normalizeAgainst($config);

        $this->assertResultsAre([
            'filename1.php',
            'filename2.php',
            'filename5.php'
        ], $results);
    }

    /** @test */
    public function it_can_normalize_against_custom_file_number()
    {
        $config = Config::create(['filesToShow' => 2]);
        $results = $this->resultCollection->normalizeAgainst($config);

        $this->assertResultsAre([
            'filename1.php',
            'filename2.php'
        ], $results);
    }

    /** @test */
    public function it_can_normalize_against_custom_min_score()
    {
        $config = Config::create(['minScoreToShow' => 0.6]);
        $results = $this->resultCollection->normalizeAgainst($config);

        $this->assertResultsAre([
            'filename1.php'
        ], $results);
    }

    /** @test */
    public function it_can_normalize_against_custom_file_number_and_min_score_1()
    {
        $config = Config::create([
            'filesToShow' => 3,
            'minScoreToShow' => 0,
        ]);
        $results = $this->resultCollection->normalizeAgainst($config);

        $this->assertResultsAre([
            'filename1.php',
            'filename2.php',
            'filename5.php'
        ], $results);
    }

    /** @test */
    public function it_can_normalize_against_custom_file_number_and_min_score_2()
    {
        $config = Config::create([
            'filesToShow' => 10,
            'minScoreToShow' => 0.05,
        ]);
        $results = $this->resultCollection->normalizeAgainst($config);

        $this->assertResultsAre([
            'filename1.php',
            'filename2.php',
            'filename5.php',
            'filename3.php',
        ], $results);
    }

    private function assertResultsAre(array $expectedFileNames, ResultCollection $collection)
    {
        $actualFileNames = array_map(function (array $data) {
            return $data[0];
        }, $collection->toArray());

        $this->assertEquals($expectedFileNames, $actualFileNames);
    }
}