<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use soIT\LaravelSeeders\Exceptions\MethodNotFoundException;
use soIT\LaravelSeeders\Executors\ExecutorInterface;

class LaravelSeederTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testConstructorWithExecutors()
    {
        $executorsMock = $this->createMock(Executors::class);
        $instance = $this->getMockForAbstractClass(LaravelSeeder::class, [$executorsMock]);

        $executors = $instance->getExecutors();
        $this->assertInstanceOf(Executors::class, $executors);
        $this->assertEquals($executorsMock, $executors);
    }

    /**
     * @throws ReflectionException
     */
    public function testConstructorWithNoExecutors()
    {
        $instance = $this->getMockForAbstractClass(LaravelSeeder::class);

        $executors = $instance->getExecutors();
        $this->assertInstanceOf(Executors::class, $executors);
    }

    /**
     * @throws Exceptions\ExecutorNotFoundException
     * @throws ReflectionException
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testGetExecutor()
    {
        $executorMock = $this->createMock(ExecutorInterface::class);

        /**
         * @var LaravelSeeder $mock
         */
        $mock = $this->getMockForAbstractClass(LaravelSeeder::class);

        $executorFactoryMock = \Mockery::mock('overload:soIT\LaravelSeeders\Executors\ExecutorFactory');
        $executorFactoryMock->shouldReceive('factory')->andReturn($executorMock);

        $retExecutor = $mock->getExecutor(6);
        $this->assertInstanceOf(ExecutorInterface::class, $retExecutor);
        $this->assertEquals($executorMock, $retExecutor);
    }

    /**
     * @throws ReflectionException
     */
    public function testMagicCall()
    {
        $executorMock = $this->createMock(ExecutorInterface::class);
        $executorsMock = $this->getMockBuilder(Executors::class)
            ->setMethods(['setTest'])
            ->getMock();

        $executorsMock->method('setTest')->will($this->returnValue($executorMock));

        /**
         * @var LaravelSeeder $mock
         */
        $mock = $this->getMockForAbstractClass(LaravelSeeder::class, [$executorsMock]);
        $executor = $mock->setTest('xxx');

        $this->assertInstanceOf(ExecutorInterface::class, $executor);
        $this->assertEquals($executorMock, $executor);
    }

    /**
     * @throws ReflectionException
     */
    public function testMagicCallMethodDontExists()
    {
        $executorsMock = $this->getMockBuilder(Executors::class)->getMock();

        /**
         * @var LaravelSeeder $mock
         */
        $mock = $this->getMockForAbstractClass(LaravelSeeder::class, [$executorsMock]);
        $this->expectException(MethodNotFoundException::class);
        $mock->setTest('xxx');
    }

    //   use SeederTestFileMock;

    //    public function setUp()
    //    {
    //        parent::setUp();
    //
    //        $this->_createTestFile();
    //    }
    //
    //    public function tearDown()
    //    {
    //        $this->_deleteTestFile();
    //
    //        parent::tearDown();
    //    }

    //    public function testSetModel()
    //    {
    //        $mock = $this->getMockForAbstractClass(LaravelSeeder::class);
    //
    //        $executorMock = \Mockery::mock('overload:ExecutorFactory');
    //        $executorMock->shouldReceive('model')->andReturn($this->getMockClass(ModelExecutor::class));
    //
    //        $this->assertInstanceOf(ModelExecutor::class, $mock->setModel('test'));
    //    }
    //
    //    public function testSetTable()
    //    {
    //        $mock = $this->getMockForAbstractClass(LaravelSeeder::class);
    //
    //        $executorMock = \Mockery::mock('overload:ExecutorFactory');
    //        $executorMock->shouldReceive('table')->andReturn($this->getMockClass(TableExecutor::class));
    //
    //        $this->assertInstanceOf(TableExecutor::class, $mock->setTable('test'));
    //    }

    //    /**
    //     * @throws Exceptions\ExecutorNotFoundException
    //     */
    //    public function testSeed()
    //    {
    //        $executorStub = \Mockery::mock(ModelExecutor::class);
    //        $executorStub->shouldReceive('data')->andReturn($executorStub);
    //        $executorStub->shouldReceive('mapping')->andReturn($executorStub);
    //        $executorStub->shouldReceive('execute')->andReturn(true);
    //
    //        /**
    //         * @var LaravelSeeder $stub
    //         */
    //        $stub = \Mockery::mock(LaravelSeeder::class)->makePartial();
    //        $stub->shouldReceive('getExecutor')->andReturn($executorStub);
    //
    //        $stub->clearTransformations();
    //        $stub->seed();
    //    }
    //
    //    /**
    //     * @throws ReflectionException
    //     */
    //    public function testClearMapping()
    //    {
    //        $stub = $this->_makeStub();
    //        $stub->assignModel('test', get_class($stub));
    //        $this->assertEquals(1, count($stub->getMapping()));
    //
    //        $stub->clearTransformations();
    //        $this->assertEquals(0, count($stub->getMapping()));
    //    }
    //
    //    /**
    //     * @throws Exceptions\DirectoryDontExistException
    //     * @throws Exceptions\FileDontExistException
    //     * @throws ReflectionException
    //     */
    //    public function testAddSource()
    //    {
    //        $stub = $this->_makeStub();
    //
    //        $retObj = $stub->addSource(new File($this->testFileName));
    //
    //        $this->assertInstanceOf(get_class($stub), $retObj);
    //        $this->assertCount(1, $stub->getSources());
    //
    //        $stub->addSource(new File($this->testFileName));
    //        $this->assertCount(2, $stub->getSources());
    //
    //        $this->assertInstanceOf(File::class, $stub->getSources()[1]);
    //    }
    //
    //    /**
    //     * @throws Exceptions\DirectoryDontExistException
    //     * @throws Exceptions\FileDontExistException
    //     * @throws ReflectionException
    //     */
    //    public function testGetData()
    //    {
    //        $stub = $this->_makeStub();
    //        $stub->addSource(new File($this->testFileName));
    //
    //        $data = $stub->getData();
    //
    //        $this->assertInstanceOf(DataContainer::class, $data);
    //        $this->assertEquals(new DataContainer($this->testData), $data);
    //
    //        $this->testData = ['test' => ['test' => '1']];
    //        $this->_deleteTestFile();
    //        $this->_createTestFile();
    //
    //        $stub = $this->_makeStub();
    //        $stub->addSource(new File($this->testFileName));
    //
    //        $data = $stub->getData();
    //
    //        $this->assertInstanceOf(DataContainer::class, $data);
    //        $this->assertEquals(new DataContainer($this->testData), $data);
    //    }
    //
    //    /**
    //     * @throws Exceptions\ExecutorNotFoundException
    //     * @throws ReflectionException
    //     */
    //    public function testGetExecutor()
    //    {
    //        $stub = $this->_makeStub();
    //
    //        $stub->setModel('Test');
    //        $executor = $stub->getExecutor();
    //        $this->assertInstanceOf(ModelExecutor::class, $executor);
    //
    //        $stub->setTable('Test');
    //        $executor = $stub->getExecutor();
    //        $this->assertInstanceOf(TableExecutor::class, $executor);
    //    }
    //
    //    /**
    //     * @throws Exceptions\DirectoryDontExistException
    //     * @throws Exceptions\FileDontExistException
    //     * @throws ReflectionException
    //     */
    //    public function testGetSource()
    //    {
    //        $stub = $this->_makeStub();
    //
    //        $stub->addSource(new File($this->testFileName));
    //
    //        $sources = $stub->getSources();
    //
    //        $this->assertIsArray($sources);
    //        $this->assertCount(1, $sources);
    //        $this->assertInstanceOf(File::class, $sources[0]);
    //    }
    //
    //    /**
    //     * @throws ReflectionException
    //     */
    //    public function testGetSetModel()
    //    {
    //        $stub = $this->_makeStub();
    //        $retInstance = $stub->setModel('TestModel');
    //
    //        $this->assertEquals('TestModel', $stub->getTarget());
    //        $this->assertEquals(LaravelSeeder::MODEL, $stub->getType());
    //        $this->assertInstanceOf(LaravelSeeder::class, $retInstance);
    //    }
    //
    //    /**
    //     * @throws ReflectionException
    //     */
    //    public function testGetSetTable()
    //    {
    //        $stub = $this->_makeStub();
    //        $retInstance = $stub->setTable('TestTable');
    //
    //        $this->assertEquals('TestTable', $stub->getTarget());
    //        $this->assertEquals(LaravelSeeder::TABLE, $stub->getType());
    //        $this->assertInstanceOf(LaravelSeeder::class, $retInstance);
    //    }
    //
    //    /**
    //     * @throws ReflectionException
    //     */
    //    public function testAssignModel()
    //    {
    //        $stub = $this->_makeStub();
    //
    //        $retObj = $stub->assignModel('id', 'LaravelSeeder');
    //
    //        $this->assertInstanceOf(get_class($stub), $retObj);
    //    }
    //
    //    /**
    //     * @return LaravelSeeder
    //     * @throws ReflectionException
    //     */
    //    protected function _makeStub(): LaravelSeeder
    //    {
    //        return $this->getMockForAbstractClass(LaravelSeeder::class);
    //    }
}
