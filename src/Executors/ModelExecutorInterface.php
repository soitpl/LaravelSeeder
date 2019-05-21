<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT {$year}
 */

namespace soIT\LaravelSeeders\Executors;

interface ModelExecutorInterface extends ExecutorInterface
{
    public function translateProperty(string $targetPropertyName, string $sourcePropertyName);
}
