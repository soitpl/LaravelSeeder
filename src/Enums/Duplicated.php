<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Enums;

class Duplicated
{
    public const __default = self::IGNORE;

    public const IGNORE = 1;
    public const UPDATE = 2;
}
