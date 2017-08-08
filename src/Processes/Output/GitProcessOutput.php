<?php declare(strict_types = 1);

namespace Churn\Processes\Output;

class GitProcessOutput
{
    public function __construct($rawOutput)
    {
        $this->output = $rawOutput;
    }
}
