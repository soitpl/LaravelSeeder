<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders\Executors;

use soIT\LaravelSeeders\Sources\SourceInterface;
use soIT\LaravelSeeders\Containers\TransformationsContainer;
use soIT\LaravelSeeders\Containers\DataContainer;

interface ExecutorInterface
{
    public function addSource(SourceInterface $source): self;
    public function execute(DataContainer $data);
    public function seed();
}
