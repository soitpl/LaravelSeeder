<?php
/**
 * HasAdditionalPropertiesTest.php
 *
 * @lastModification 14.05.2020, 09:38
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Tests\Unit\Executors\Traits;

use PHPUnit\Framework\MockObject\MockObject;
use soIT\LaravelSeeder\Containers\AdditionalPropertiesContainer;
use soIT\LaravelSeeder\Executors\Traits\HasAdditionalProperties;
use PHPUnit\Framework\TestCase;

class HasAdditionalPropertiesTest extends TestCase
{

    public function testAddPropertyWithCallback()
    {
        /**
         * @var HasAdditionalProperties|MockObject $instance
         */
        $instance = $this->getMockBuilder(HasAdditionalProperties::class)
                         ->onlyMethods(['createAdditionalPropertiesContainer'])
                         ->getMockForTrait();

        $instance->expects($this->once())->method('createAdditionalPropertiesContainer')
                 ->willReturn($this->createStub(AdditionalPropertiesContainer::class));

        $ret = $instance->getAdditionalProperties();
        $this->assertInstanceOf(AdditionalPropertiesContainer::class, $ret);

        $ret1 = $instance->getAdditionalProperties();
        $this->assertInstanceOf(AdditionalPropertiesContainer::class, $ret);
        $this->assertEquals($ret, $ret1);
    }
}
