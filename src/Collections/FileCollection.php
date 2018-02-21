<?php declare(strict_types = 1);

namespace Churn\Collections;

use Churn\Values\File;
use Tightenco\Collect\Support\Collection;

class FileCollection extends Collection
{
    /**
     * Determines if the collection has any files left.
     * @return boolean
     */
    public function hasFiles(): bool
    {
        return $this->count() > 0;
    }

    /**
     * Pops off the next file from the collection.
     * @return File
     */
    public function getNextFile(): File
    {
        return $this->shift();
    }
}
