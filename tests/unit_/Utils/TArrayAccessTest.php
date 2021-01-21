<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Utils\ArrayAccessTrait;

class TArrayAccessTest extends TestCase
{
    /**
     * @var ArrayAccessTrait
     */
    private $testObject;
    private $testData = ['x', 'y', 'z'];

    public function setUp():void
    {
        $this->testObject = Mockery::mock('soIT\LaravelSeeders\Utils\ArrayAccessTrait')->makePartial();
        $this->testObject->setItems($this->testData);
        parent::setUp();
    }

    public function testOffsetExists()
    {
        $this->assertFalse($this->testObject->offsetExists(7));
        $this->assertTrue($this->testObject->offsetExists(1));
    }

    public function testOffsetGet()
    {
        $this->assertEquals($this->testData[0], $this->testObject->offsetGet(0));
        $this->assertEquals($this->testData[2], $this->testObject->offsetGet(2));
    }

    public function testOffsetSet()
    {
        $this->testObject->offsetSet(3, 'h');
        $this->testObject->offsetSet(4, 'k');
        $this->assertEquals('h', $this->testObject->offsetGet(3));
        $this->assertEquals('k', $this->testObject->offsetGet(4));

        $this->testObject->offsetSet(null, 'i');
        $this->assertEquals('i', $this->testObject->offsetGet(5));
    }

    public function testOffsetUnset()
    {
        $this->testObject->offsetUnset(2);
        $this->assertNull($this->testObject->offsetGet(2));
    }
}
