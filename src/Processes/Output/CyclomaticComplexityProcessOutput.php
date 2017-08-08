<?php declare(strict_types = 1);

namespace Churn\Processes\Output;

class CyclomaticComplexityProcessOutput
{
    public function __construct($rawOutput)
    {
        $this->output = $rawOutput;
    }
}
