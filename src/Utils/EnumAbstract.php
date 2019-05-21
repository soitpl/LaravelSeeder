<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
 */


/**
 * Class ListDefinitionAbstract

 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2017-date('Y')
 */
abstract class Enum
{
//    /**
//     * @var bool True if list value should by translate before return
//     */
//    public static $multilanguage = false;
//
//    /**
//     * Pobieranie nazwy elementu
//     *
//     * @param string $elem Element
//     *
//     * @return string|array Nazwa elementy
//     */
//    public static function get(string $elem = null)
//    {
//        if (!$elem) {
//            try {
//                $reflector = new \ReflectionClass(get_called_class());
//                return $reflector->getConstants();
//            } catch (\ReflectionException $e) {
//                CoreException::catch('');
//                return null;
//            }
//        } else {
//            $elem = strtoupper($elem);
//
//            $staticName = self::_createStaticName($elem);
//
//            if (defined($staticName)) {
//                $elem = constant($staticName);
//            }
//
//            return self::$multilanguage ? t($elem) : $elem;
//        }
//    }
//
//    /**
//     * get symbol by name
//     *
//     * @param string $name Nazwa
//     * @param bool $part Czy szukać także częsci
//     *
//     * @return bool|string
//     */
//    public static function getByName(string $name, bool $part = false): ?string
//    {
//        try {
//            $reflector = new \ReflectionClass(get_called_class());
//        } catch (\ReflectionException $e) {
//            CoreException::catch('');
//            return null;
//        }
//        $constants = $reflector->getConstants();
//
//        foreach ($constants as $k => $v) {
//            if ((!$part && $v == $name) || ($part && (stripos($v, $name) !== false))) {
//                return $k;
//            }
//        }
//
//        return null;
//    }
//
//    /**
//     * Check is element defined in class
//     *
//     * @param string $elem Element to check
//     * @return bool
//     */
//    public static function isExist(string $elem): bool
//    {
//        return defined(self::_createStaticName($elem));
//    }
//
//    /**
//     * Get all defined elements
//     */
//    public static function getList(): array
//    {
//        return array_keys(self::get());
//    }
//
//    /**
//     * Create static name for checking
//     *
//     * @param string $name Name to create static name
//     *
//     * @return string
//     */
//    private static function _createStaticName(string $name): string
//    {
//        return 'static::' . $name;
//    }
}
