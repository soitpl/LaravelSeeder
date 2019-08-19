<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders;

use Illuminate\Database\Seeder;
use soIT\LaravelSeeders\Exceptions\ExecutorNotFoundException;
use soIT\LaravelSeeders\Exceptions\MethodNotFoundException;
use soIT\LaravelSeeders\Executors\ExecutorFactory;
use soIT\LaravelSeeders\Executors\ExecutorInterface;

/**
 * @method Executors\TableExecutor setTable(string $string)
 * @method Executors\ModelExecutor setModel(string $string)
 */
class LaravelSeeder extends Seeder
{
    /**
     * @var Executors Executors class container
     */
    protected $executors = null;

    /**
     * LaravelSeeder constructor.
     *
     * @param Executors $executors Executors container
     */
    public function __construct(Executors $executors = null)
    {
        $this->setExecutors($executors ?? new Executors());
    }

    /**
     * Set instance of executors class
     *
     * @return Executors
     */
    public function getExecutors(): Executors
    {
        return $this->executors;
    }

    /**
     * Set instance of executors class
     *
     * @param Executors $executors Executors class
     */
    public function setExecutors(Executors $executors): void
    {
        $this->executors = $executors;
    }

    /**
     * Get proper executor
     *
     * @param int $type Executor type
     * @param mixed[] $arguments
     *
     * @return ExecutorInterface
     * @throws ExecutorNotFoundException
     */
    public function getExecutor(int $type, ...$arguments): ExecutorInterface
    {
        return (new ExecutorFactory($this->executors))->factory($type, $arguments);
    }

    /**
     * Magic call method
     *
     * @param string $name Function name
     * @param array $arguments Function arguments
     *
     * @return ExecutorInterface
     * @throws MethodNotFoundException
     */
    public function __call($name, $arguments): ExecutorInterface
    {
        if (method_exists($this->executors, $name)) {
            return $this->executors->$name(...$arguments);
        } else {
            throw new MethodNotFoundException(
                sprintf('Method %s not found in class %s', $name, get_class($this->executors))
            );
        }
    }
}
