<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders\Executors\Features;

use soIT\LaravelSeeders\Containers\AdditionalPropertiesContainer;
use soIT\LaravelSeeders\Executors\ExecutorAbstract;

trait AdditionalPropertiesTrait
{
    /**
     * @var AdditionalPropertiesContainer
     */
    private $properties;

    /**
     * Getter for translations container
     *
     * @return AdditionalProperiesConatiner
     */
    public function getAdditionalProperties(): AdditionalPropertiesContainer
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
    public function addPropertyWithConstant(string $property, $value): ExecutorAbstract
    {
        $this->getAdditionalProperties()->assignConstant($property, $value);

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
    public function addPropertyWithCallback(string $property, callable $callback): ExecutorAbstract
    {
        $this->getAdditionalProperties()->assignCallback($property, $callback);

        return $this;
    }

    /**
     * Setter for translations container
     *
     * @param AdditionalPropertiesContainer|null $properties
     *
     * @return self|ExecutorAbstract
     */
    public function setAdditionalProperties(?AdditionalPropertiesContainer $properties): ExecutorAbstract
    {
        $this->properties = $properties;

        return $this;
    }
}
