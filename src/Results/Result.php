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
     * @var integer
     */
    private $commits;

    /**
     * The complexity property.
     * @var integer
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
        return $this->getCommits() + $this->getComplexity();
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
