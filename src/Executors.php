<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders;

use soIT\LaravelSeeders\Executors\ExecutorFactory;
use soIT\LaravelSeeders\Executors\ExecutorInterface;
use soIT\LaravelSeeder\Executors\ModelExecutor;
use soIT\LaravelSeeder\Executors\TableExecutor;

/**
 * Class Executors
 * @package soIT\LaravelSeeders
 * @deprecated
 */
class Executors
{
    public const MODEL = 0;
    public const TABLE = 1;
    public const CLASSES = [ModelExecutor::class, TableExecutor::class];

    /**
     * Set model as a target for seeder
     *
     * @param string $modelName
     *
     * @return ExecutorInterface
     * @throws Exceptions\ExecutorNotFoundException
     */
    public function setModel(string $modelName): ExecutorInterface
    {
        return (new ExecutorFactory($this))->factory(self::MODEL, $modelName);
    }

    /**
     * Set model as a target for seeder
     *
     * @param string $tableName
     *
     * @return ExecutorInterface
     * @throws Exceptions\ExecutorNotFoundException
     */
    public function setTable(string $tableName): ExecutorInterface
    {
        return (new ExecutorFactory($this))->factory(self::TABLE, $tableName);
    }
}
