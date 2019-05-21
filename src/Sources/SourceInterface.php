<?php
/**
 *
 *
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
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
