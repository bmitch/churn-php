<?php

use Mockery as m;
use Churn\Tests\BaseTestCase;
use Churn\Renderers\Results\JsonResultsRenderer;
use Churn\Results\ResultCollection;
use Symfony\Component\Console\Output\OutputInterface;

class JsonResultsRendererTest extends BaseTestCase
{
    /** @test **/
    public function testRender()
    {
        $inputData = [
            [
                0 => '0_0_key_test',
                1 => '0_1_key_test',
                2 => '0_2_key_test',
                3 => '0_3_key_test',
            ],
            [
                0 => '1_0_key_test',
                1 => '1_1_key_test',
                2 => '1_2_key_test',
                3 => '1_3_key_test',
            ],
        ];

        $outputData = json_encode([
            [
                'file' => '0_0_key_test',
                'commits' => '0_1_key_test',
                'complexity' => '0_2_key_test',
                'score' => '0_3_key_test',
            ],
            [
                'file' => '1_0_key_test',
                'commits' => '1_1_key_test',
                'complexity' => '1_2_key_test',
                'score' => '1_3_key_test',
            ],
        ]);

        $output = m::mock(OutputInterface::class);
        $output->shouldReceive('write')->atLeast()->once()->with($outputData);

        $result = m::mock(ResultCollection::class);
        $result->shouldReceive('toArray')->andReturn($inputData);

        $renderer = new JsonResultsRenderer;
        $renderer->render($output, $result);
    }

    /**
     * @inheritdoc
     */
    public function tearDown()
    {
        m::close();
    }
}
