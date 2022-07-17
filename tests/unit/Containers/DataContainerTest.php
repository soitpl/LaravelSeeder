<?php
/**
 * LaravelSeeder Library
 *
 * @file DataContainerTest.php
 * @lastModification 15.05.2020, 09:27
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeder\Tests\Unit\Containers;

use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeder\Containers\DataContainer;
use soIT\LaravelSeeders\Exceptions\WrongAttributeException;

class DataContainerTest extends TestCase
{
    public function testConstructor()
    {
        $testData = ['x' => ['y' => ['z' => ['a', 'b', 'c']]]];
        $dc = new DataContainer($testData);

        $this->assertInstanceOf(DataContainer::class, $dc);
        $this->assertInstanceOf(DataContainer::class, $dc['x']);
        $this->assertInstanceOf(DataContainer::class, $dc['x']['y']);
        $this->assertInstanceOf(DataContainer::class, $dc['x']['y']['z']);
    }

    public function testGet()
    {
        $testData = ['x' => 'y', 'a' => 'b'];
        $dc = new DataContainer($testData);

        $this->assertEquals('y', $dc->x);
        $this->assertEquals('b', $dc->a);
    }

    public function testGetWithException()
    {
        $testData = ['x' => 'y', 'a' => 'b'];
        $dc = new DataContainer($testData);

        $this->expectException(WrongAttributeException::class);
        $dc->z;
    }
}
