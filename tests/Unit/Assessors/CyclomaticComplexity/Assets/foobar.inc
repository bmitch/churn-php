<?php declare(strict_types = 1);

namespace Churn\Assessors\CyclomaticComplexity;

class CyclomaticComplexityAssessor
{
    /**
     * The total cyclomatic complexity score.
     * @var integer.
     */
    protected $score;

    /**
     * Asses the files cyclomatic complexity.
     * @param  string $filePath Path and file name.
     * @return integer
     */
    public function assess($filePath): int
    {
        $this->score = 0;

        $contents = $this->getFileContents($filePath);

        $this->countTheMethods($contents);
        $this->countTheIfStatements($contents);
        $this->countTheElseIfStatements($contents);
        $this->countTheWhileLoops($contents);
        $this->countTheForLoops($contents);

        $this->countTheCaseStatements($contents);

        if ($this->score == 0) {
            $this->score = 1;
        }
        return $this->score;
    }

    /**
     * Count how many methods there are.
     * @param  string $contents Path and filename.
     * @return void
     */
    protected function countTheMethods($contents)
    {
        preg_match("/[ ]function[ ]/", $contents, $matches);
        if (isset($matches[0])) {
            $this->score ++;
        }
    }

    /**
     * Count how many if statements there are.
     * @param  string $contents Path and filename.
     * @return void
     */
    protected function countTheIfStatements($contents)
    {
        $this->score += $this->howmAnyPatternMatches("/[ ]if[ ]/", $contents);
    }

    /**
     * Count how many else if statements there are.
     * @param  string $contents Path and filename.
     * @return void
     */
    protected function countTheElseIfStatements($contents)
    {
        $this->score += $this->howmAnyPatternMatches("/elseif/", $contents);
    }

    /**
     * Count how many while loops there are.
     * @param  string $contents Path and filename.
     * @return void
     */
    protected function countTheWhileLoops($contents)
    {
        $this->score += $this->howmAnyPatternMatches("/while/", $contents);
    }

    /**
     * Count how many for loops there are.
     * @param  string $contents Path and filename.
     * @return void
     */
    protected function countTheForLoops($contents)
    {
        $this->score += $this->howmAnyPatternMatches("/for(each){0,1}[ ]{0,}\(/", $contents);
    }

    /**
     * Count how many case statements there are.
     * @param  string $contents Path and filename.
     * @return void
     */
    protected function countTheCaseStatements($contents)
    {
        $this->score += $this->howmAnyPatternMatches("/case/", $contents);
    }

    /**
     * For the given $pattern on $string, how many matches are returned?
     * @param  string $pattern Regex pattern.
     * @param  string $string  Any string.
     * @return integer
     */
    protected function howManyPatternMatches($pattern, $string): int
    {
        preg_match_all($pattern, $string, $matches);
        if (isset($matches[0])) {
            return count($matches[0]);
        }
        return 0;
    }

    /**
     * Return the contents of the provided file at $filePath.
     * @param  string $filePath Path and filename.
     * @return string
     */
    protected function getFileContents($filePath): string
    {
        return file_get_contents($filePath);
    }
}
