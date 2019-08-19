<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
 */
namespace soIT\LaravelSeeders\Containers;

use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Transformations\CallableTransformation;
use soIT\LaravelSeeders\Transformations\TransformationsInterface;

use Illuminate\Contracts\Console\Kernel;

class TransformationsContainerTest extends TestCase
{
    public function testAssign()
    {
        $transformationMock = \Mockery::mock(TransformationsInterface::class);

        $container = new TransformationsContainer();
        $container->assign('test', $transformationMock);

        $this->assertEquals($transformationMock, $container->getTransformation('test'));
    }

    public function testGetTransformation()
    {
        $transformationMock = \Mockery::mock(TransformationsInterface::class);

        $container = new TransformationsContainer();
        $container->assign('test', $transformationMock);

        $this->assertEquals($transformationMock, $container->getTransformation('test'));
        $this->assertNull($container->getTransformation('xxx'));
    }

    public function testGetValue()
    {
        $testValue = Str::random(15);
        $testProperty = Str::random(15);
        $container = new TransformationsContainer();

        $transformationMock = \Mockery::mock(TransformationsInterface::class);
        $transformationMock
            ->shouldReceive('setPropertyName')
            ->withArgs([$testProperty])
            ->andReturn($transformationMock);
        $transformationMock
            ->shouldReceive('transform')
            ->withArgs([$testValue, TransformationsContainer::class])
            ->andReturn('ok');

        $container->assign($testProperty, $transformationMock);

        $this->assertEquals('ok', $container->getValue($testProperty, $testValue));
        $this->assertEquals($testValue, $container->getValue($testProperty . '-x', $testValue));
    }

    public function testAssignCallback()
    {
        $function = function ($value) {
            return $value . '-tested';
        };

        $container = new TransformationsContainer();
        $container->assignCallback('test', $function);

        $this->assertInstanceOf(CallableTransformation::class, $container->getTransformation('test'));
        $this->assertEquals('test-tested', $container->getTransformation('test')->transform('test', $container));
    }

    public function testCount()
    {
        $transformationMock = \Mockery::mock(TransformationsInterface::class);

        $container = new TransformationsContainer();
        $container->assign('test', $transformationMock);
        $container->assign('test-1', $transformationMock);
        $this->assertEquals(2, $container->count());
    }
}
