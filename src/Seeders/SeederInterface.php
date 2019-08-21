<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders\Seeders;

use soIT\LaravelSeeders\Containers\DataContainer;
use soIT\LaravelSeeders\Containers\TransformationsContainer;

interface SeederInterface
{
    public function onDuplicate(int $duplicated): SeederInterface;
    public function setData(DataContainer $data): SeederInterface;
    public function setTransformations(TransformationsContainer $transformations);
    public function save();
}
