<?php
/**
 * LaravelSeeder Library
 *
 * @file HasPropertiesTransformationTest.php
 * @lastModification 15.05.2020, 07:45
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Tests\Unit\Executors\Traits;

use PHPUnit\Framework\MockObject\MockObject;
use soIT\LaravelSeeder\Containers\TransformationsContainer;
use soIT\LaravelSeeder\Executors\Traits\HasPropertiesTransformation;
use PHPUnit\Framework\TestCase;

class HasPropertiesTransformationTest extends TestCase
{
    public function testGetTransformations()
    {
        /**
         * @var HasPropertiesTransformation|MockObject $instance
         */
        $instance = $this->getMockBuilder(HasPropertiesTransformation::class)
                         ->onlyMethods(['createTransformationsContainer'])
                         ->getMockForTrait();

        $instance->expects($this->once())->method('createTransformationsContainer')
                 ->willReturn($this->createStub(TransformationsContainer::class));

        $ret = $instance->getTransformations();
        $this->assertInstanceOf(TransformationsContainer::class, $ret);

        $ret1 = $instance->getTransformations();
        $this->assertInstanceOf(TransformationsContainer::class, $ret);
        $this->assertEquals($ret, $ret1);
    }
}
