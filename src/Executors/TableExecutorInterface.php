<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Executors;

interface TableExecutorInterface extends ExecutorInterface
{
    public function translateProperty(string $targetPropertyName, string $sourcePropertyName);
}
