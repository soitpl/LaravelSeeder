<?php
/**
 * LaravelSeeder Library
 *
 * @file HasNamingStrategyTest.php
 * @lastModification 15.05.2020, 07:58
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Tests\Unit\Executors\Traits;

use PHPUnit\Framework\MockObject\MockObject;
use soIT\LaravelSeeder\Containers\NamingStrategyContainer;
use soIT\LaravelSeeder\Executors\Traits\HasNamingStrategy;
use PHPUnit\Framework\TestCase;

class HasNamingStrategyTest extends TestCase
{

    public function testGetNamingStrategy()
    {
        /**
         * @var HasNamingStrategy|MockObject $instance
         */
        $instance = $this->getMockBuilder(HasNamingStrategy::class)
                         ->onlyMethods(['createNamingStrategyContainer'])
                         ->getMockForTrait();

        $instance->expects($this->once())->method('createNamingStrategyContainer')
                 ->willReturn($this->createStub(NamingStrategyContainer::class));

        $ret = $instance->getNamingStrategy();
        $this->assertInstanceOf(NamingStrategyContainer::class, $ret);

        $ret1 = $instance->getNamingStrategy();
        $this->assertInstanceOf(NamingStrategyContainer::class, $ret);
        $this->assertEquals($ret, $ret1);
    }
}
