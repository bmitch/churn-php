<?php

declare(strict_types=1);

namespace Churn\Process;

use Churn\File\File;
use Churn\Process\ChangesCount\FossilChangesCountProcess;
use Churn\Process\ChangesCount\GitChangesCountProcess;
use Churn\Process\ChangesCount\MercurialChangesCountProcess;
use Churn\Process\ChangesCount\NoVcsChangesCountProcess;
use Churn\Process\ChangesCount\SubversionChangesCountProcess;
use Closure;
use DateTime;
use InvalidArgumentException;

/**
 * @internal
 */
class ChangesCountProcessBuilder
{
    /**
     * @param string $vcs Name of the version control system.
     * @param string $commitsSince String containing the date of when to look at commits since.
     * @return Closure(File):ChangesCountInterface
     * @throws InvalidArgumentException If VCS is not supported.
     */
    public function getBuilder(string $vcs, string $commitsSince): Closure
    {
        switch ($vcs) {
            case 'git':
                return $this->getGitChangesCountProcessBuilder($commitsSince);
            case 'subversion':
                return $this->getSubversionChangesCountProcessBuilder($commitsSince);
            case 'mercurial':
                return $this->getMercurialChangesCountProcessBuilder($commitsSince);
            case 'fossil':
                return $this->getFossilChangesCountProcessBuilder($commitsSince);
            case 'none':
                return $this->getNoVcsChangesCountProcessBuilder();
            default:
                throw new InvalidArgumentException('Unsupported VCS: ' . $vcs);
        }
    }

    /**
     * @param string $commitsSince String containing the date of when to look at commits since.
     * @return Closure(File):ChangesCountInterface
     */
    private function getGitChangesCountProcessBuilder(string $commitsSince): Closure
    {
        return static function (File $file) use ($commitsSince): ChangesCountInterface {
            return new GitChangesCountProcess($file, $commitsSince);
        };
    }

    /**
     * @param string $commitsSince String containing the date of when to look at commits since.
     * @return Closure(File):ChangesCountInterface
     */
    private function getSubversionChangesCountProcessBuilder(string $commitsSince): Closure
    {
        $dateRange = \sprintf(
            '{%s}:{%s}',
            \date('Y-m-d', \strtotime($commitsSince)),
            (new DateTime('tomorrow'))->format('Y-m-d')
        );

        return static function (File $file) use ($dateRange): ChangesCountInterface {
            return new SubversionChangesCountProcess($file, $dateRange);
        };
    }

    /**
     * @param string $commitsSince String containing the date of when to look at commits since.
     * @return Closure(File):ChangesCountInterface
     */
    private function getMercurialChangesCountProcessBuilder(string $commitsSince): Closure
    {
        $date = \date('Y-m-d', \strtotime($commitsSince));

        return static function (File $file) use ($date): ChangesCountInterface {
            return new MercurialChangesCountProcess($file, $date);
        };
    }

    /**
     * @param string $commitsSince String containing the date of when to look at commits since.
     * @return Closure(File):ChangesCountInterface
     */
    private function getFossilChangesCountProcessBuilder(string $commitsSince): Closure
    {
        $date = \date('Y-m-d', \strtotime($commitsSince));

        return static function (File $file) use ($date): ChangesCountInterface {
            return new FossilChangesCountProcess($file, $date);
        };
    }

    /**
     * Returns a builder for NoVcsChangesCountProcess.
     *
     * @return Closure(File):ChangesCountInterface
     */
    private function getNoVcsChangesCountProcessBuilder(): Closure
    {
        return static function (File $file): ChangesCountInterface {
            return new NoVcsChangesCountProcess($file);
        };
    }
}
