<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */
namespace soIT\LaravelSeeders;

use soIT\LaravelSeeders\Exceptions\ExecutorNotFoundException;
use soIT\LaravelSeeders\Executors\ExecutorFactory;
use soIT\LaravelSeeders\Executors\ModelExecutor;
use Tests\TestCase;

class ExecutorFactoryTest extends TestCase
{
    /**
     * @throws Exceptions\ExecutorNotFoundException
     */
    public function testFactory()
    {
        /**
         * @var Executors $executors
         */
        $executors = $this->createMock(Executors::class);
        $instance = new ExecutorFactory($executors);

        $executor = $instance->factory(Executors::MODEL, 'ModelName');

        $this->assertInstanceOf(ModelExecutor::class, $executor);
    }

    /**
     * @throws Exceptions\ExecutorNotFoundException
     */
    public function testFactoryWithWrongExecutor()
    {
        /**
         * @var Executors $executors
         */
        $executors = $this->createMock(Executors::class);
        $instance = new ExecutorFactory($executors);

        $this->expectException(ExecutorNotFoundException::class);
        $instance->factory(10, 'ModelName');
    }
}
