<?php
/**
 * AdditionalPropertiesContainer.php
 *
 * @lastModification 16.11.2019, 18:20
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Containers;

use ArrayAccess;
use Countable;
use Iterator;
use soIT\LaravelSeeder\Transformations\CallableTransformation;
use soIT\LaravelSeeders\Utils\ArrayAccessTrait;
use soIT\LaravelSeeders\Utils\CountableTrait;
use soIT\LaravelSeeders\Utils\IteratorTrait;

class AdditionalPropertiesContainer implements Iterator, ArrayAccess, Countable
{
    use ArrayAccessTrait;
    use CountableTrait;
    use IteratorTrait;

    /**
     * Assign constant value to property
     *
     * @param string $property
     * @param $value
     */
    public function assignValue(string $property, $value):void
    {
        $this->items[$property] = $value;
    }

    /**
     * Assign callback function to property
     *
     * @param string $property
     * @param callable $callback
     */
    public function assignCallback(string $property, callable $callback):void
    {
        $this->assignValue($property, new CallableTransformation($callback));
    }

    public function hasAdditionalProperties():bool
    {
        return (bool)count($this->items);
    }

    public function addPropertiesToItem(DataContainer $item):DataContainer
    {
        foreach ($this->items as $key => $value) {
            return $item->put($key, $this->getValue($value));
        }

        return $item;
    }

    private function getValue(mixed $value):mixed
    {
        return $value instanceof CallableTransformation ? $value->transform() : $value;
    }
}
