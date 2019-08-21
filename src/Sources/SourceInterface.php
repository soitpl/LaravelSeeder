<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Sources;

interface SourceInterface
{
    /**
     * Return collection with data to seed
     *
     * @return mixed
     */
    public function data();
}
