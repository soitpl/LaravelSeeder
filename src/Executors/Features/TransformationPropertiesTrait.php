<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
 */

namespace soIT\LaravelSeeders\Executors\Features;

use soIT\LaravelSeeders\Executors\ExecutorAbstract;
use soIT\LaravelSeeders\Containers\TransformationsContainer;

trait TransformationPropertiesTrait
{
    /**
     * @var TransformationsContainer
     */
    private $transformations;

    /**
     * Getter for translations container
     *
     * @return TransformationsContainer
     */
    public function getTransformations(): TransformationsContainer
    {
        return $this->transformations ?? ($this->transformations = new TransformationsContainer());
    }

    /**
     * Setter for translations container
     *
     * @param TransformationsContainer|null $translations
     *
     * @return self|ExecutorAbstract
     */
    public function setTransformations(?TransformationsContainer $translations): ExecutorAbstract
    {
        $this->transformations = $translations;

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
    public function assignCallback(string $property, callable $callback): ExecutorAbstract
    {
        $this->getTransformations()->assignCallback($property, $callback);

        return $this;
    }
}
