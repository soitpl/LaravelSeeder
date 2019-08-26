<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Transformations;

use soIT\LaravelSeeders\Containers\TransformationsContainer;

interface TransformationsInterface
{
    public function setPropertyName(string $property): TransformationsInterface;
    public function setTransformationsContainer(TransformationsContainer $container);
    public function transform($propertyValue);


}
