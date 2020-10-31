<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Utils;

use soIT\LaravelSeeders\Containers\DataContainer;
use stdClass;

class Converters
{
    /**
     * Convert array to object recurrently
     *
     * @param array $array Array to convert
     *
     * @return DataContainer
     */
    public static function arrayToObject(array $array): stdClass
    {
        $object = new stdClass();

        if (count($array) > 0) {
            foreach ($array as $key => $value) {
                $key = strtolower(trim($key));
                $object->$key = is_array($value) ? self::arrayToObject($value) : $value;
            }
        }

        return $object;
    }

    /**
     * Explode string to array
     *
     * @param string $ident Ident to parse
     * @return array Parsed array
     */
    public static function stringToArray(string $ident): array
    {
        return explode('.', $ident);
    }
}
