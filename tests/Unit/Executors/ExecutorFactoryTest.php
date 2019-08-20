<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders;

use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Exceptions\ExecutorNotFoundException;
use soIT\LaravelSeeders\Executors\ExecutorFactory;
use soIT\LaravelSeeders\Executors\ModelExecutor;

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
