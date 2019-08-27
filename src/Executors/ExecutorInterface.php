<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Executors;

use soIT\LaravelSeeders\Sources\SourceInterface;
use soIT\LaravelSeeders\Containers\DataContainer;

interface ExecutorInterface
{
    public function addSource(SourceInterface $source);
    public function execute(DataContainer $data);
    public function seed();
}
