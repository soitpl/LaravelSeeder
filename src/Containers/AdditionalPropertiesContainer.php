<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders\Containers;

use soIT\LaravelSeeders\Utils\ArrayAccessTrait;
use soIT\LaravelSeeders\Utils\CountableTrait;
use soIT\LaravelSeeders\Utils\IteratorTrait;

class AdditionalPropertiesContainer
{
    use IteratorTrait, ArrayAccessTrait, CountableTrait;

    /**
     * Assign constant value to property
     *
     * @param $property
     * @param $value
     */
    public function assignConstant($property, $value) {
        $this->items[$property] = $value;
    }
}
