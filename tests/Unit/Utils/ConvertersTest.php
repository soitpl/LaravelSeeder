<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Containers;

use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Utils\Converters;

class ConvertersTest extends TestCase
{
    private $testArray = ['x', 'y' => ['a', 'b', 'c'], 'g' => '23'];

    public function testArrayToObject()
    {
        $object = Converters::arrayToObject($this->testArray);
        $this->assertIsObject($object);
        $this->assertInstanceOf(\stdClass::class, $object);
        $this->assertNotEmpty($object->y);
        $this->assertInstanceOf(\stdClass::class, $object->y);
        $this->assertNotEmpty($object->g);
        $this->assertEquals(23, $object->g);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Property [h] does not exist on this collection instance.');
        $this->assertEmpty($object->h);
    }

    public function testStringToArray()
    {
        $string = 'Test.Test2.Test3';
        $array = Converters::stringToArray($string);
        $this->assertIsArray($array);
        $this->assertCount(3, $array);
        $this->assertEquals('Test2', $array[1]);
    }
}
