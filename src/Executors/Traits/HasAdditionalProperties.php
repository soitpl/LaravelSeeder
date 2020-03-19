<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Executors\Traits;

use soIT\LaravelSeeder\Containers\AdditionalPropertiesContainer;
use soIT\LaravelSeeder\Executors\ExecutorAbstract;

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
        return $this->properties ?? ($this->properties = new AdditionalPropertiesContainer());
    }

    /**
     * Assign callback action to property
     *
     * @param string $property
     * @param $value
     *
     * @return self|ExecutorAbstract
     */
    public function addPropertyWithValue(string $property, $value):ExecutorAbstract
    {
        $this->getAdditionalProperties()->assignValue($property, $value);

        return $this;
    }

    /**
     * Assign callback action to property
     *
     * @param string  Property name
     * @param callable $callback Callback function
     *
     * @return self|ExecutorAbstract
     */
    public function addPropertyWithCallback(string $property, callable $callback):ExecutorAbstract
    {
        $this->getAdditionalProperties()->assignCallback($property, $callback);

        return $this;
    }
}
