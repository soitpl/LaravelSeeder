<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Enums;

class Duplicated
{
    const __default = self::IGNORE;

    const IGNORE = 1;
    const UPDATE = 2;
}
