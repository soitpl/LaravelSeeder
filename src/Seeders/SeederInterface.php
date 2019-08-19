<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */
namespace soIT\LaravelSeeders\Seeders;

use soIT\LaravelSeeders\Containers\AdditionalProperiesConatiner;
use soIT\LaravelSeeders\Containers\DataContainer;

interface SeederInterface
{
    public function onDuplicate(int $duplicated): SeederInterface;
    public function setData(DataContainer $data): SeederInterface;
    public function setTransformations(AdditionalProperiesConatiner $transformations);
    public function save();
}
