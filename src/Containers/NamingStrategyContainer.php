<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeder\Containers;

use ArrayAccess;
use Countable;
use Iterator;
use soIT\LaravelSeeders\Utils\ArrayAccessTrait;
use soIT\LaravelSeeders\Utils\CountableTrait;
use soIT\LaravelSeeders\Utils\IteratorTrait;

class NamingStrategyContainer implements Iterator, ArrayAccess, Countable
{
    use ArrayAccessTrait;
    use CountableTrait;
    use IteratorTrait;

    /**
     * Assign element to property name
     *
     * @param string $sourceProperty Property name
     * @param string $targetProperty
     */
    public function add(string $targetProperty, string $sourceProperty): void
    {
        $this->items[$sourceProperty] = $targetProperty;
    }

    /**
     * Get translated property name if set
     *
     * @param string $property
     *
     * @return string|null
     */
    public function get(string $property): ?string
    {
        return $this->items[$property] ?? $property;
    }
}
