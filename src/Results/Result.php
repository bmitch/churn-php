<?php declare(strict_types = 1);

namespace Churn\Results;

class Result
{
    /**
     * The file property.
     * @var string
     */
    private $file;

    /**
     * The commits property.
     * @var string
     */
    private $commits;

    /**
     * The complexity property.
     * @var string
     */
    private $complexity;

    /**
     * Class constructor.
     * @param array $data Data to store in the object.
     */
    public function __construct(array $data)
    {
        $this->file       = $data['file'];
        $this->commits    = $data['commits'];
        $this->complexity = $data['complexity'];
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
     * @return string
     */
    public function getCommits(): string
    {
        return $this->commits;
    }

    /**
     * Get the file property.
     * @return string
     */
    public function getComplexity(): string
    {
        return $this->complexity;
    }

    /**
     * Calculate the score.
     * @return string
     */
    public function getScore(): string
    {
        return $this->getCommits() + $this->getComplexity();
    }

    /**
     * Output results to an array.
     * @return string
     */
    public function toArray(): string
    {
        return [
            $this->getFile(),
            $this->getCommits(),
            $this->getComplexity(),
            $this->getScore(),
        ];
    }
}
