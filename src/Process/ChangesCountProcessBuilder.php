<?php

declare(strict_types=1);

namespace Churn\Process;

use Churn\File\File;
use Churn\Process\ChangesCount\FossilChangesCountProcess;
use Churn\Process\ChangesCount\GitChangesCountProcess;
use Churn\Process\ChangesCount\MercurialChangesCountProcess;
use Churn\Process\ChangesCount\NoVcsChangesCountProcess;
use Closure;
use InvalidArgumentException;

class ChangesCountProcessBuilder
{

    /**
     * @param string $vcs Name of the version control system.
     * @param string $commitsSince String containing the date of when to look at commits since.
     * @throws InvalidArgumentException If VCS is not supported.
     */
    public function getBuilder(string $vcs, string $commitsSince): Closure
    {
        if ('git' === $vcs) {
            return $this->getGitChangesCountProcessBuilder($commitsSince);
        }

        if ('mercurial' === $vcs) {
            return $this->getMercurialChangesCountProcessBuilder($commitsSince);
        }

        if ('fossil' === $vcs) {
            return $this->getFossilChangesCountProcessBuilder($commitsSince);
        }

        if ('none' === $vcs) {
            return static function (File $file): ChangesCountInterface {
                return new NoVcsChangesCountProcess($file);
            };
        }

        throw new InvalidArgumentException('Unsupported VCS: ' . $vcs);
    }

    /**
     * @param string $commitsSince String containing the date of when to look at commits since.
     */
    private function getGitChangesCountProcessBuilder(string $commitsSince): Closure
    {
        return static function (File $file) use ($commitsSince): ChangesCountInterface {
            return new GitChangesCountProcess($file, $commitsSince);
        };
    }

    /**
     * @param string $commitsSince String containing the date of when to look at commits since.
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
     */
    private function getFossilChangesCountProcessBuilder(string $commitsSince): Closure
    {
        $date = \date('Y-m-d', \strtotime($commitsSince));

        return static function (File $file) use ($date): ChangesCountInterface {
            return new FossilChangesCountProcess($file, $date);
        };
    }
}
