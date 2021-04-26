<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Transformations;

use soIT\LaravelSeeder\Containers\TransformationsContainer;

interface TransformationsInterface
{
    public function setTransformationsContainer(TransformationsContainer $container);
    public function transform($propertyValue);
}
