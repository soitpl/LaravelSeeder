<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders;

use Mockery;
use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeder\Executors\ModelExecutor;
use soIT\LaravelSeeder\Executors\TableExecutor;

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
        $instance = new Seeders();
        $factoryMock = Mockery::mock('overload:soIT\LaravelSeeders\Executors\ExecutorFactory');
        $factoryMock->shouldReceive('factory')->andReturn($this->createMock(TableExecutor::class));

        /**
         * @var ModelExecutor $ex
         */
        $ex = $instance->setTable($this->testTarget);
        $this->assertInstanceOf(TableExecutor::class, $ex);
    }

    /**
     * @throws Exceptions\ExecutorNotFoundException
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testSetModel()
    {
        $instance = new Seeders();
        $factoryMock = Mockery::mock('overload:soIT\LaravelSeeders\Executors\ExecutorFactory');
        $factoryMock->shouldReceive('factory')->andReturn($this->createMock(ModelExecutor::class));

        /**
         * @var TableExecutor $ex
         */
        $ex = $instance->setModel($this->testTarget);
        $this->assertInstanceOf(ModelExecutor::class, $ex);
    }
}
