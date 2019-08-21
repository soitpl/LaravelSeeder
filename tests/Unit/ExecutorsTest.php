<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders;

use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Executors\ModelExecutor;

class ExecutorsTest extends TestCase
{
    private $testTarget = 'TargetTest';
    /**
     * @throws Exceptions\ExecutorNotFoundException
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testSetTable()
    {
        $instance = new Executors();
        $factoryMock = \Mockery::mock('overload:soIT\LaravelSeeders\Executors\ExecutorFactory');
        $factoryMock->shouldReceive('factory')->andReturn($this->createMock(Executors\TableExecutor::class));

        /**
         * @var ModelExecutor $ex
         */
        $ex = $instance->setTable($this->testTarget);
        $this->assertInstanceOf(Executors\TableExecutor::class, $ex);
    }

    /**
     * @throws Exceptions\ExecutorNotFoundException
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testSetModel()
    {
        $instance = new Executors();
        $factoryMock = \Mockery::mock('overload:soIT\LaravelSeeders\Executors\ExecutorFactory');
        $factoryMock->shouldReceive('factory')->andReturn($this->createMock(Executors\ModelExecutor::class));

        /**
         * @var Executors\TableExecutor $ex
         */
        $ex = $instance->setModel($this->testTarget);
        $this->assertInstanceOf(Executors\ModelExecutor::class, $ex);
    }
}
