<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Utils\IteratorTrait;

class IteratorTraitTest extends TestCase
{
    private const TEST_ARRAY = [9, 8, 7, 6, 5, 4];

    public function testRewind()
    {
        $mock = $this->getTraitMock();
        $mock->next();
        $mock->next();
        $mock->next();
        $mock->next();
        $mock->rewind();
        $this->assertEquals(self::TEST_ARRAY[0], $mock->current());
    }

    public function testCurrent()
    {
        $mock = $this->getTraitMock();
        $this->assertEquals(self::TEST_ARRAY[0], $mock->current());
        $mock->next();
        $this->assertEquals(self::TEST_ARRAY[1], $mock->current());
        $mock->next();
        $mock->next();
        $mock->next();
        $this->assertEquals(self::TEST_ARRAY[4], $mock->current());
    }

    public function testKey()
    {
        $mock = $this->getTraitMock();
        $this->assertEquals(0, $mock->key());
        $mock->next();
        $mock->next();
        $this->assertEquals(2, $mock->key());
    }

    public function testNext()
    {
        $mock = $this->getTraitMock();
        $mock->next();
        $this->assertEquals(self::TEST_ARRAY[1], $mock->current());
        $mock->next();
        $this->assertEquals(self::TEST_ARRAY[2], $mock->current());
        $mock->next();
        $mock->next();
        $mock->next();
        $mock->next();
        $mock->next();
        $mock->next();
        $this->assertFalse($mock->current());
    }

    public function testAll()
    {
        $mock = $this->getTraitMock();
        $this->assertEquals(self::TEST_ARRAY, $mock->all());
    }

    public function testSet()
    {
        $testArray = [3, 4, 5, 6, 'test'];
        $mock = $this->getTraitMock();
        $mock->set($testArray);
        $this->assertEquals($testArray, $mock->all());
    }

    public function testValid()
    {
        $mock = $this->getTraitMock();
        $this->assertTrue($mock->valid());
        for ($i = 0; $i < 10; $i++) {
            $mock->next();
        }
        $this->assertFalse($mock->valid());
    }

    private function getTraitMock()
    {
        $mock = \Mockery::mock(IteratorTrait::class);
        $mock->set(self::TEST_ARRAY);
        return $mock;
    }
}
