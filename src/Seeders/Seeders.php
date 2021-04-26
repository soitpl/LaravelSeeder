<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeder\Seeders;

use soIT\LaravelSeeders\Exceptions\ExecutorNotFoundException;
use soIT\LaravelSeeders\Executors\ExecutorFactory;
use soIT\LaravelSeeders\Executors\ExecutorInterface;
use soIT\LaravelSeeder\Executors\ModelExecutor;
use soIT\LaravelSeeder\Executors\TableExecutor;

/**
 * Class Seeders
 * @package soIT\LaravelSeeder\Seeders
 * @deprecated 
 */
class Seeders
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
     * @throws ExecutorNotFoundException
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
     * @throws ExecutorNotFoundException
     */
    public function setTable(string $tableName): ExecutorInterface
    {
        return (new ExecutorFactory($this))->factory(self::TABLE, $tableName);
    }
}
