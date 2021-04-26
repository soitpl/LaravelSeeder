<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Executors\Traits;

use soIT\LaravelSeeder\Containers\AdditionalPropertiesContainer;

trait HasAdditionalProperties
{
    /**
     * @var AdditionalPropertiesContainer
     */
    private AdditionalPropertiesContainer $properties;

    /**
     * Getter for translations container
     *
     * @return AdditionalPropertiesContainer
     */
    public function getAdditionalProperties():AdditionalPropertiesContainer
    {
        return $this->properties ?? ($this->properties = $this->createAdditionalPropertiesContainer());
    }

    public function addPropertyWithValue(string $property, $value):self
    {
        $this->getAdditionalProperties()->assignValue($property, $value);

        return $this;
    }

    public function addPropertyWithCallback(string $property, callable $callback):self
    {
        $this->getAdditionalProperties()->assignCallback($property, $callback);

        return $this;
    }

    protected function createAdditionalPropertiesContainer():AdditionalPropertiesContainer
    {
        return new AdditionalPropertiesContainer();
    }

    private function addPropertiesToItem(mixed $item):mixed
    {
        if ($this->getAdditionalProperties()->hasAdditionalProperties()) {
            return $this->getAdditionalProperties()->addPropertiesToItem($item);
        }

        return $item;
    }
}
