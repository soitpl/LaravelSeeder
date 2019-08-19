<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT {$year}
 */

namespace soIT\LaravelSeeders\Sources;

use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Exceptions\WrongAttributeException;

class EntityTest extends TestCase
{
    private $testArray = ['x' => '1', 'y' => ['a', 'b', 'c'], 'g' => '23'];

    public function testConstructor()
    {
        $instance = new Entity($this->testArray);
        $this->assertEquals([$this->testArray], $instance->data());
    }

    public function testConstructorWithWrongArray()
    {
        $this->expectException(WrongAttributeException::class);
        $instance = new Entity(['x', 'y', 'x']);
    }
}
