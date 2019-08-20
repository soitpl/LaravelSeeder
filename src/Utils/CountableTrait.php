<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Utils;

trait CountableTrait
{
    /**
     * @var array Items array
     */
    private $items = [];

    /**
     * Count number of set elements
     *
     * @return int Number od mappings
     */
    public function count(): int
    {
        return count($this->items);
    }
}
