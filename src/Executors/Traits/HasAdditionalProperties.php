<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Executors\Traits;

use soIT\LaravelSeeder\Containers\AdditionalPropertiesContainer;
use soIT\LaravelSeeder\Contracts\ExecutorInterface;

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

    /**
     * Assign callback action to property
     *
     * @param string $property
     * @param $value
     *
     * @return self|ExecutorInterface
     * @codeCoverageIgnore
     */
    public function addPropertyWithValue(string $property, $value):ExecutorInterface
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
     * @return self|ExecutorInterface
     * @codeCoverageIgnore
     */
    public function addPropertyWithCallback(string $property, callable $callback):ExecutorInterface
    {
        $this->getAdditionalProperties()->assignCallback($property, $callback);

        return $this;
    }

    /**
     * @return AdditionalPropertiesContainer
     * @codeCoverageIgnore
     */
    protected function createAdditionalPropertiesContainer():AdditionalPropertiesContainer
    {
        return new AdditionalPropertiesContainer();
    }
}
