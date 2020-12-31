<?php
/**
 * LaravelSeeder Library
 *
 * @file HasSourcesTest.php
 * @lastModification 15.05.2020, 08:19
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Tests\Unit\Executors\Traits;

use Faker\Provider\Base;
use Faker\Provider\Lorem;
use PHPUnit\Framework\MockObject\MockObject;
use soIT\LaravelSeeder\Containers\DataContainer;
use soIT\LaravelSeeder\Contracts\SourceInterface;
use soIT\LaravelSeeder\Executors\Traits\HasSources;
use PHPUnit\Framework\TestCase;

class HasSourcesTest extends TestCase
{
    public function testProceedSources()
    {
        $word = Lorem::word();
        $no = Base::numberBetween(3, 20);

        /**
         * @var HasSources|MockObject $instance
         */
        $instance = $this->getMockBuilder(HasSources::class)->getMockForTrait();

        for ($i = 0; $i < $no; $i++) {
            $data = ['x'.$i => $word];

            $mock = $this->createMock(SourceInterface::class);
            $mock->expects($this->once())->method('data')->willReturn($data);

            $instance->addSource($mock);
        }

        $ret = $instance->proceedSources();
        $this->assertInstanceOf(DataContainer::class, $ret);
        $this->assertEquals($no, $ret->count());
    }

    public function testAddSource()
    {
        $no = Base::numberBetween(3, 20);

        /**
         * @var HasSources|MockObject $instance
         */
        $instance = $this->getMockBuilder(HasSources::class)->getMockForTrait();

        for ($i = 0; $i < $no; $i++) {
            $instance->addSource($this->createStub(SourceInterface::class));
        }

        $this->assertCount($no, $instance->getSources());
    }
}
