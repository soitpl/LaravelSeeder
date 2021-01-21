<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Executors\Traits;

use soIT\LaravelSeeder\Containers\TransformationsContainer;
use soIT\LaravelSeeder\Contracts\ExecutorInterface;
use soIT\LaravelSeeder\Executors\ExecutorAbstract;


trait HasPropertiesTransformation
{
    /**
     * @var TransformationsContainer
     */
    private ?TransformationsContainer $transformations = null;

    /**
     * Getter for translations container
     *
     * @return TransformationsContainer
     */
    public function getTransformations():TransformationsContainer
    {
        return $this->transformations ?? ($this->transformations = $this->createTransformationsContainer());
    }

    /**
     * Setter for translations container
     *
     * @param TransformationsContainer|null $translations
     *
     * @return self|ExecutorAbstract
     * @codeCoverageIgnore
     */
    public function setTransformations(?TransformationsContainer $translations):ExecutorInterface
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
     * @codeCoverageIgnore
     */
    public function assignTransformation(string $property, callable $callback):ExecutorInterface
    {
        $this->getTransformations()->assignCallback($property, $callback);

        return $this;
    }

    /**
     * @return TransformationsContainer
     * @codeCoverageIgnore
     */
    protected function createTransformationsContainer(): TransformationsContainer {
        return new TransformationsContainer();
    }
}
