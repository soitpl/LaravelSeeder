<?php
/**
 *
 *
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders\Parsers;

interface ParserInterface
{
    public function parse(): array;
}
