<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Executors;

use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Containers\DataContainer;
use soIT\LaravelSeeders\Seeders\SeederAbstract;

use soIT\LaravelSeeders\Sources\SourceInterface;

class ExecutorAbstractTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testProceedSources()
    {
        /**
         * @var ExecutorAbstract $executorMock
         * @var SourceInterface $sourceMock
         * @var SourceInterface $sourceMock2
         */
        $array = ['x' => 'y'];
        $array2 = ['a' => 'b'];
        $executorMock = $this->_getExecutorMock();
        $sourceMock = $this->createMock(SourceInterface::class);
        $sourceMock->method('data')->willReturn($array);

        $sourceMock2 = $this->createMock(SourceInterface::class);
        $sourceMock2->method('data')->willReturn($array2);

        $executorMock->addSource($sourceMock);
        $data = $executorMock->proceedSources();

        $this->assertInstanceOf(DataContainer::class, $data);
        $this->assertEquals(new DataContainer($array), $data);

        $executorMock->addSource($sourceMock2);

        $data = $executorMock->proceedSources();

        $this->assertInstanceOf(DataContainer::class, $data);
        $this->assertEquals(new DataContainer(array_merge($array, $array2)), $data);
    }

    /**
     * @throws \ReflectionException
     */
    public function testAddGetSource()
    {
        /**
         * @var ExecutorAbstract $executorMock
         * @var SourceInterface $sourceMock
         */
        $executorMock = $this->_getExecutorMock();
        $sourceMock = $this->createMock(SourceInterface::class);

        $returnedObj = $executorMock->addSource($sourceMock);
        $this->assertInstanceOf(ExecutorInterface::class, $returnedObj);
        $this->assertCount(1, $executorMock->getSources());
        $executorMock->addSource($sourceMock);
        $this->assertCount(2, $executorMock->getSources());
    }

    public function testOnDuplicate()
    {
        $executorMock = $this->_getExecutorMock();

        $seeder = \Mockery::mock(SeederAbstract::class);
        $seeder
            ->shouldReceive('onDuplicate')
            ->withArgs([1])
            ->andReturn($seeder);

        $executorMock->setSeeder($seeder);

        $ret = $executorMock->onDuplicate(1);

        $this->assertEquals($executorMock, $ret);
    }

    /**
     * @throws \ReflectionException
     */
    public function testSeed()
    {
        $executorMock = $this->_getExecutorMock();

        $ret = $executorMock->seed();
        $this->assertTrue($ret);
    }

    public function testSetGetSeeder()
    {
        $seeder = \Mockery::mock(SeederAbstract::class);

        $executorMock = $this->_getExecutorMock();
        $ret = $executorMock->setSeeder($seeder);

        $this->assertEquals($executorMock, $ret);
        $this->assertEquals($seeder, $ret->getSeeder());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     * @throws \ReflectionException
     */
    private function _getExecutorMock()
    {
        return $this->getMockForAbstractClass(ExecutorAbstract::class);
    }
}
