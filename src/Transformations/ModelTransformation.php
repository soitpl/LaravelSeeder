<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
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
    private $propertyName;

    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;
    }

    /**
     * Make transformation for model
     *
     * @param $propertyValue
     * @param TransformationsContainer $transformationsContainer
     *
     * @return mixed
     */
    public function transform($propertyValue, TransformationsContainer $transformationsContainer)
    {
        return (new RelationModelSeeder($this->modelName))
            ->setTransformations($transformationsContainer)
            ->setData($propertyValue);
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
