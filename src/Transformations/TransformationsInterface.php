<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT {$year}
 */

namespace soIT\LaravelSeeders\Transformations;

use soIT\LaravelSeeders\Containers\TransformationsContainer;

interface TransformationsInterface
{
    public function setPropertyName(string $property): TransformationsInterface;
    public function transform($propertyValue, TransformationsContainer $transformationsContainer);
}
