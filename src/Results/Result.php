<?php declare(strict_types = 1);

namespace Churn\Results;

use Churn\Values\Config;

class Result
{
    /**
     * The file property.
     * @var string
     */
    private $file;

    /**
     * The commits property.
     * @var integer
     */
    private $commits;

    /**
     * The complexity property.
     * @var integer
     */
    private $complexity;

    /**
     * The config values.
     * @var Config
     */
    private $config;

    /**
     * Class constructor.
     * @param array $data Data to store in the object.
     */
    public function __construct(array $data, Config $config)
    {
        $this->file       = $data['file'];
        $this->commits    = $data['commits'];
        $this->complexity = $data['complexity'];
        $this->config     = $config;
    }

    /**
     * Get the file property.
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Get the file property.
     * @return integer
     */
    public function getCommits(): int
    {
        return $this->commits;
    }

    /**
     * Get the file property.
     * @return integer
     */
    public function getComplexity(): int
    {
        return $this->complexity;
    }

    /**
     * Calculate the score.
     * @return integer
     */
    public function getScore(): int
    {
        $replacement = [
            '[[commits]]' => $this->getCommits(),
            '[[complexity]]' => $this->getComplexity()
        ];
        $formula = str_replace(array_keys($replacement), $replacement, $this->config->getFormula());

        return eval('return ' . $formula . ';');
    }

    /**
     * Output results to an array.
     * @return array
     */
    public function toArray(): array
    {
        return [
            $this->getFile(),
            $this->getCommits(),
            $this->getComplexity(),
            $this->getScore(),
        ];
    }
}
