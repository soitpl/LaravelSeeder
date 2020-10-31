<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeder\Seeders;

use soIT\LaravelSeeder\Containers\DataContainer;
use soIT\LaravelSeeder\Containers\TransformationsContainer;

interface SeederInterface
{
    public function onDuplicate(int $duplicated): SeederInterface;
    public function setData(DataContainer $data): SeederInterface;
    public function setTransformations(TransformationsContainer $transformations);
    public function save();
}
