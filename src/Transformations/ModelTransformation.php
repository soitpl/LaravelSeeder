<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Transformations;

use soIT\LaravelSeeders\Containers\TransformationsContainer;
use soIT\LaravelSeeders\Seeders\RelationModelSeeder;

class ModelTransformation implements TransformationsInterface
{
    /**
     * @var string Model name
     */
    private $modelName;
    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var TransformationsContainer
     */
    private $transformationsContainer;

    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;
    }

    /**
     * Make transformation for model
     *
     * @param mixed $propertyValue
     *
     * @return mixed
     */
    public function transform($propertyValue)
    {
        return (new RelationModelSeeder($this->modelName))
            ->setTransformations($this->transformationsContainer)
            ->setData($propertyValue);
    }

    /**
     * @param TransformationsContainer $container
     *
     * @return TransformationsInterface
     */
    public function setTransformationsContainer(TransformationsContainer $container) : TransformationsInterface {
        $this->transformationsContainer = $container;

        return $this;
    }

    /**
     * @param string $property
     *
     * @return TransformationsInterface
     */
    public function setPropertyName(string $property): TransformationsInterface
    {
        $this->propertyName = $property;

        return $this;
    }
}
