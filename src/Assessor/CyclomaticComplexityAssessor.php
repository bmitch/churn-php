<?php

declare(strict_types=1);

namespace Churn\Assessor;

/**
 * @internal
 */
class CyclomaticComplexityAssessor
{
    /**
     * The total cyclomatic complexity score.
     *
     * @var integer
     */
    private $score = 0;

    /**
     * Asses the files cyclomatic complexity.
     *
     * @param string $filePath Path and file name.
     */
    public function assess(string $filePath): int
    {
        $this->score = 0;
        $contents = $this->getFileContents($filePath);
        if (false !== $contents) {
            $this->hasAtLeastOneMethod($contents);
            $this->countTheIfStatements($contents);
            $this->countTheElseIfStatements($contents);
            $this->countTheWhileLoops($contents);
            $this->countTheForLoops($contents);
            $this->countTheCaseStatements($contents);
            $this->countTheTernaryOperators($contents);
            $this->countTheLogicalAnds($contents);
            $this->countTheLogicalOrs($contents);
        }

        if (0 === $this->score) {
            $this->score = 1;
        }

        return $this->score;
    }

    /**
     * Does the class have at least one method?
     *
     * @param string $contents File contents.
     */
    private function hasAtLeastOneMethod(string $contents): void
    {
        \preg_match("/[ ]function[ ]/", $contents, $matches);

        if (!isset($matches[0])) {
            return;
        }

        $this->score++;
    }

    /**
     * Count how many if statements there are.
     *
     * @param string $contents File contents.
     */
    private function countTheIfStatements(string $contents): void
    {
        $this->score += $this->howManyPatternMatches("/[ ]if[ ]{0,}\(/", $contents);
    }

    /**
     * Count how many else if statements there are.
     *
     * @param string $contents File contents.
     */
    private function countTheElseIfStatements(string $contents): void
    {
        $this->score += $this->howManyPatternMatches("/else[ ]{0,}if[ ]{0,}\(/", $contents);
    }

    /**
     * Count how many while loops there are.
     *
     * @param string $contents File contents.
     */
    private function countTheWhileLoops(string $contents): void
    {
        $this->score += $this->howManyPatternMatches("/while[ ]{0,}\(/", $contents);
    }

    /**
     * Count how many for loops there are.
     *
     * @param string $contents File contents.
     */
    private function countTheForLoops(string $contents): void
    {
        $this->score += $this->howManyPatternMatches("/[ ]for(each){0,1}[ ]{0,}\(/", $contents);
    }

    /**
     * Count how many case statements there are.
     *
     * @param string $contents File contents.
     */
    private function countTheCaseStatements(string $contents): void
    {
        $this->score += $this->howManyPatternMatches("/[ ]case[ ]{1}(.*)\:/", $contents);
    }

    /**
     * Count how many ternary operators there are.
     *
     * @param string $contents File contents.
     */
    private function countTheTernaryOperators(string $contents): void
    {
        $this->score += $this->howManyPatternMatches("/[ ]\?.*:.*;/", $contents);
    }

    /**
     * Count how many '&&' there are.
     *
     * @param string $contents File contents.
     */
    private function countTheLogicalAnds(string $contents): void
    {
        $this->score += $this->howManyPatternMatches("/[ ]&&[ ]/", $contents);
    }

    /**
     * Count how many '||' there are.
     *
     * @param string $contents File contents.
     */
    private function countTheLogicalOrs(string $contents): void
    {
        $this->score += $this->howManyPatternMatches("/[ ]\|\|[ ]/", $contents);
    }

    /**
     * For the given $pattern on $string, how many matches are returned?
     *
     * @param string $pattern Regex pattern.
     * @param string $string Any string.
     */
    private function howManyPatternMatches(string $pattern, string $string): int
    {
        return (int) \preg_match_all($pattern, $string);
    }

    /**
     * Return the contents of the provided file at $filePath.
     *
     * @param string $filePath Path and filename.
     * @return string|false The file content.
     */
    private function getFileContents(string $filePath)
    {
        return \file_get_contents($filePath);
    }
}
