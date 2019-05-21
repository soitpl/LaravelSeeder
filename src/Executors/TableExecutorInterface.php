<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT {$year}
 */

namespace soIT\LaravelSeeders\Executors;

interface TableExecutorInterface extends ExecutorInterface
{
    public function translateProperty(string $targetPropertyName, string $sourcePropertyName);
}
