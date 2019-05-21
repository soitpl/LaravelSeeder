<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders\Utils;

use soIT\LaravelSeeders\Containers\DataContainer;

class Converters
{
    /**
     * Convert array to object recurrently
     *
     * @param array $array Array to convert
     *
     * @return DataContainer
     */
    public static function arrayToObject(array $array): DataContainer
    {
        $object = new \stdClass();
        if (count($array) > 0) {
            foreach ($array as $key => $value) {
                $key = strtolower(trim($key));
                if (is_array($value)) {
                    $object->$key = self::arrayToObject($value);
                } else {
                    $object->$key = $value;
                }
            }
        }

        return new DataContainer($object);
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
