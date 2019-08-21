<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders\Executors;

use soIT\LaravelSeeders\Exceptions\ExecutorNotFoundException;
use soIT\LaravelSeeders\Executors;

class ExecutorFactory
{
    /**
     * @var Executors Executors instance
     */
    private $executors;

    public function __construct(Executors $executors)
    {
        $this->executors = $executors;
    }

    /**
     * Get proper executor object
     *
     * @param string $type Executor type
     * @param array ...$attribute
     *
     * @return ExecutorInterface
     * @throws ExecutorNotFoundException
     */
    public function factory(string $type, ...$attribute): ExecutorInterface
    {
        $class = $this->executors::CLASSES[$type] ?? null;

        if ($class === null) {
            throw new ExecutorNotFoundException(sprintf('Seed executor for %s type was not found', $type));
        } else {
            return new $class(...$attribute);
        }
    }
}
