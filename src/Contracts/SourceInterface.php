<?php
/**
 * LaravelSeeder Library
 *
 * @file SourceInterface.php
 * @lastModification 16.11.2019, 18:20
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Contracts;

interface SourceInterface
{
    /**
     * Return collection with data to seed
     *
     * @return mixed
     */
    public function data(): array;
}
