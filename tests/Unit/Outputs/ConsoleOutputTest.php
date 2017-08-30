<?php declare(strict_types = 1);

namespace Churn\Tests\Results;

use Churn\Values\Config;
use Churn\Results\Result;
use Churn\Tests\BaseTestCase;
use Churn\Commands\ChurnCommand;
use Churn\Results\ResultCollection;

class ConsoleOutputTest extends BaseTestCase
{
    /**
     * The object we're testing.
     * @var ResultCollection
     */
    protected $results;

    /** @test */
    public function it_can_handle_results_and_returns_array()
    {
        $commend = new ChurnCommand();
        $resultsArray = $commend->handleResults($this->results);

        $this->assertSame(gettype($resultsArray), 'array');
    }

    /** @test */
    public function it_can_handle_results_with_no_custom_config()
    {
        $commend = new ChurnCommand();
        $resultsArray = $commend->handleResults($this->results);

        $this->assertSame(5, count($resultsArray));
        $this->assertSame(['filename3.php', 7, 9, 16], $resultsArray[0]);
        $this->assertSame(['filename2.php', 6, 8, 14], $resultsArray[1]);
        $this->assertSame(['filename1.php', 5, 7, 12], $resultsArray[2]);
        $this->assertSame(['filename5.php', 0, 1, 1], $resultsArray[3]);
        $this->assertSame(['filename4.php', 0, 0, 0], $resultsArray[4]);
    }

    /** @test */
    public function it_can_handle_results_with_custom_file_number()
    {
        $commend = new ChurnCommand();
        $commend->setConfig(new Config(['filesToShow' => 2]));
        $resultsArray = $commend->handleResults($this->results);

        $this->assertSame(2, count($resultsArray));
        $this->assertSame(['filename3.php', 7, 9, 16], $resultsArray[0]);
        $this->assertSame(['filename2.php', 6, 8, 14], $resultsArray[1]);
    }

    /** @test */
    public function it_can_handle_results_with_custom_min_score()
    {
        $commend = new ChurnCommand();
        $commend->setConfig(new Config(['minScoreToShow' => 15]));
        $resultsArray = $commend->handleResults($this->results);

        $this->assertSame(1, count($resultsArray));
        $this->assertSame(['filename3.php', 7, 9, 16], $resultsArray[0]);
    }

    /** @test */
    public function it_can_handle_results_with_custom_file_number_and_min_score_1()
    {
        $commend = new ChurnCommand();
        $commend->setConfig(new Config([
            'filesToShow' => 3,
            'minScoreToShow' => 1,
        ]));
        $resultsArray = $commend->handleResults($this->results);

        $this->assertSame(3, count($resultsArray));
        $this->assertSame(['filename3.php', 7, 9, 16], $resultsArray[0]);
        $this->assertSame(['filename2.php', 6, 8, 14], $resultsArray[1]);
        $this->assertSame(['filename1.php', 5, 7, 12], $resultsArray[2]);
    }

    /** @test */
    public function it_can_handle_results_with_custom_file_number_and_min_score_2()
    {
        $commend = new ChurnCommand();
        $commend->setConfig(new Config([
            'filesToShow' => 4,
            'minScoreToShow' => 13,
        ]));
        $resultsArray = $commend->handleResults($this->results);

        $this->assertSame(2, count($resultsArray));
        $this->assertSame(['filename3.php', 7, 9, 16], $resultsArray[0]);
        $this->assertSame(['filename2.php', 6, 8, 14], $resultsArray[1]);
    }

    public function setUp()
    {
        $this->results = new ResultCollection([
            new Result(['file' => 'filename1.php', 'commits' => 5, 'complexity' => 7]),
            new Result(['file' => 'filename2.php', 'commits' => 6, 'complexity' => 8]),
            new Result(['file' => 'filename3.php', 'commits' => 7, 'complexity' => 9]),
            new Result(['file' => 'filename4.php', 'commits' => 0, 'complexity' => 0]),
            new Result(['file' => 'filename5.php', 'commits' => 0, 'complexity' => 1]),
        ]);
    }
}