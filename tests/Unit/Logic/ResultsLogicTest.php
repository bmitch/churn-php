<?php declare(strict_types=1);

namespace Churn\Tests\Unit\Logic;

use Churn\Results\Result;
use Churn\Results\ResultCollection;
use Churn\Results\ResultsParser;
use Illuminate\Support\Collection;
use Churn\Logic\ResultsLogic;

class ResultsLogicTest extends \Churn\Tests\BaseTestCase
{

    /** @var \PHPUnit_Framework_MockObject_MockObject|ResultsParser */
    private $parser;

    public function setUp()
    {
        $this->parser = $this->createMock(ResultsParser::class);
    }

    /**
     * @test
     * @covers \Churn\Logic\ResultsLogic
     */
    public function it_can_be_instantiated_using_results_parser()
    {
        $this->assertInstanceOf(ResultsLogic::class, $this->newUpResultsLogic());
    }

    /**
     * @test
     * @covers \Churn\Logic\ResultsLogic
     */
    public function it_can_parse_completed_processes_with_parser()
    {
        $completedProcesses = $this->configureProcesses();

        $resultCollection = new ResultCollection();
        $this->configureParserMock($completedProcesses, $resultCollection);

        $logic = $this->newUpResultsLogic();
        $logic->process($completedProcesses, 0, 0);
    }

    /**
     * @test
     * @covers \Churn\Logic\ResultsLogic
     */
    public function it_filters_results_collection_with_given_minimum_score()
    {
        $completedProcesses = $this->configureProcesses();

        $passingResult = $this->mockResult(1);
        $rejectedResult = $this->mockResult(0.5);
        $resultCollection = new ResultCollection([$passingResult, $rejectedResult]);

        $this->assertCount(2, $resultCollection);
        $this->configureParserMock($completedProcesses, $resultCollection);

        $logic = $this->newUpResultsLogic();
        $processed = $logic->process($completedProcesses, 0.6, 10);

        $this->assertCount(1, $processed);
    }

    /**
     * @test
     * @covers \Churn\Logic\ResultsLogic
     */
    public function it_orders_results_by_score_descending()
    {
        $completedProcesses = $this->configureProcesses();

        $resultCollection = new ResultCollection([
            $m = $this->mockResult(0.3),
            $this->mockResult(0.5)
        ]);

        $scores = $resultCollection->map->getScore(1, 1);
        $this->assertFalse($scores->first() > $scores->last());

        $this->configureParserMock($completedProcesses, $resultCollection);
        $logic = $this->newUpResultsLogic();
        $processed = $logic->process($completedProcesses, 0, 10);

        $scores = $processed->map->getScore(1, 1);
        $this->assertTrue($scores->first() > $scores->last());
    }

    /**
     * @test
     * @covers \Churn\Logic\ResultsLogic
     */
    public function it_returns_a_given_number_of_results()
    {
        $completedProcesses = $this->configureProcesses();

        $resultCollection = new ResultCollection();
        for ($i = 0; $i < 5; $i++)
            $resultCollection->push($this->mockResult($i));

        $this->assertCount(5, $resultCollection);

        $this->configureParserMock($completedProcesses, $resultCollection);
        $logic = $this->newUpResultsLogic();

        $processed = $logic->process($completedProcesses, 0, 3);
        $this->assertCount(3, $processed);
    }

    /**
     * Initialize the class under test.
     * @return ResultsLogic
     */
    protected function newUpResultsLogic(): ResultsLogic
    {
        return new ResultsLogic($this->parser);
    }

    /**
     * Configure the mocked parser to return accept processes and return results.
     * @param $completedProcesses
     * @param $resultCollection
     */
    protected function configureParserMock($completedProcesses, $resultCollection)
    {
        $this->parser
            ->expects($this->once())
            ->method('parse')
            ->with($completedProcesses)
            ->willReturn($resultCollection);
    }

    /**
     * Initialize a Result object mock with given score
     * @param $score
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockResult(float $score): \PHPUnit\Framework\MockObject\MockObject
    {
        $passingResult = $this->createMock(Result::class);
        $passingResult->method('getScore')->willReturn($score);
        return $passingResult;
    }

    /**
     * @return Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function configureProcesses()
    {
        return $this->createMock(Collection::class);
    }
}
