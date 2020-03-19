<?php
/**
 * Factory.php
 *
 * @lastModification 19.03.2020, 23:03
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder;

use soIT\LaravelSeeder\Exceptions\SeedTargetFoundException;
use soIT\LaravelSeeder\Executors\TableExecutor;
use soIT\LaravelSeeders\Containers\TransformationsContainer;
use soIT\LaravelSeeder\Executors\ModelExecutor;

class Factory
{
    /**
     * @param string $modelName
     * @param TransformationsContainer|null $transformations
     *
     * @return \soIT\LaravelSeeder\Executors\ModelExecutor
     */
    public static function model(string $modelName, TransformationsContainer $transformations = null):ModelExecutor
    {
        return new ModelExecutor($modelName, $transformations);
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
        return new TableExecutor($tableName, $transformations);
    }
}