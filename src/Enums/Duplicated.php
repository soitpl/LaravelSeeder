<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT {$year}
 */

namespace soIT\LaravelSeeders\Enums;

class Duplicated
{
    const __default = self::IGNORE;

    const IGNORE = 1;
    const UPDATE = 2;
}
