<?php
/**
 * LaravelSeeder Library
 *
 * @file SeederFactory.php
 * @lastModification 14.05.2020, 09:15
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder;

use soIT\LaravelSeeder\Containers\TransformationsContainer;
use soIT\LaravelSeeder\Exceptions\SeedTargetFoundException;
use soIT\LaravelSeeder\Executors\TableExecutor;
use soIT\LaravelSeeder\Executors\ModelExecutor;
use soIT\LaravelSeeder\Seeders\ModelSeeder;
use soIT\LaravelSeeder\Seeders\TableSeeder;

class SeederFactory
{
    public static function model(string $modelName, TransformationsContainer $transformations = null):ModelExecutor
    {
        return new ModelExecutor(new ModelSeeder($modelName), $transformations);
    }

    /**
     * @param string $tableName
     *
     * @param TransformationsContainer|null $transformations
     *
     * @return TableExecutor
     * @throws SeedTargetFoundException
     */
    public static function table(string $tableName, TransformationsContainer $transformations = null):TableExecutor
    {
        return new TableExecutor(new TableSeeder($tableName), $transformations);
    }
}