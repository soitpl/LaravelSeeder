<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Tests\Unit\Containers;

use Faker\Provider\Lorem;
use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeder\Containers\TransformationsContainer;
use soIT\LaravelSeeder\Transformations\CallableTransformation;
use soIT\LaravelSeeder\Transformations\TransformationsInterface;

class TransformationsContainerTest extends TestCase
{
    public function testAssign()
    {
        $transformationMock = $this->createStub(TransformationsInterface::class);

        $container = new TransformationsContainer();
        $container->assign('test', $transformationMock);

        $this->assertEquals($transformationMock, $container->getTransformation('test'));
    }

    public function testGetTransformation()
    {
        $transformationMock = $this->createStub(TransformationsInterface::class);

        $container = new TransformationsContainer();
        $container->assign('test', $transformationMock);

        $this->assertEquals($transformationMock, $container->getTransformation('test'));
        $this->assertNull($container->getTransformation('xxx'));
    }

    public function testGetValue()
    {
        $testValue = Lorem::word();
        $testProperty = Lorem::word();

        $container = new TransformationsContainer();

        $transformationMock = $this->getMockBuilder(TransformationsInterface::class)
                                   ->onlyMethods(['setPropertyName', 'transform', 'setTransformationsContainer'])
                                   ->getMock();

        $transformationMock->expects($this->once())->method('setPropertyName')->with($testProperty)->willReturnSelf();
        $transformationMock->expects($this->once())->method('transform')->with($testValue)->willReturn('ok');
        $transformationMock->expects($this->once())->method('setTransformationsContainer')->willReturnSelf();

        $container->assign($testProperty, $transformationMock);

        $this->assertEquals('ok', $container->getValue($testProperty, $testValue));
    }

    public function testGetValueWithNoSetProperty()
    {
        $testValue = Lorem::word();
        $testProperty = Lorem::word();

        $container = new TransformationsContainer();

        $this->assertEquals($testValue, $container->getValue($testProperty, $testValue));
    }

    public function testAssignCallback()
    {
        $function = function ($value) {
            return $value.'-tested';
        };

        $container = new TransformationsContainer();
        $container->assignCallback('test', $function);

        $this->assertInstanceOf(CallableTransformation::class, $container->getTransformation('test'));
        $this->assertEquals('test-tested', $container->getTransformation('test')->transform('test', $container));
    }

    public function testCount()
    {
        $transformationMock = $this->createMock(TransformationsInterface::class);

        $container = new TransformationsContainer();
        $container->assign('test', $transformationMock);
        $container->assign('test-1', $transformationMock);
        $this->assertEquals(2, $container->count());
    }
}
