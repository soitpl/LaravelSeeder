<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Transformations;

use soIT\LaravelSeeder\Containers\TransformationsContainer;
use soIT\LaravelSeeder\Seeders\RelationModelSeeder;
use soIT\LaravelSeeder\Seeders\SeederAbstract;

class ModelTransformation implements TransformationsInterface
{
    private string $modelName;
    private TransformationsContainer $transformationsContainer;

    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;
    }

    public function transform(mixed $propertyValue):SeederAbstract
    {
        return (new RelationModelSeeder($this->modelName))
            ->setTransformations($this->transformationsContainer)
            ->setData($propertyValue);
    }

    public function setTransformationsContainer(TransformationsContainer $container):TransformationsInterface
    {
        $this->transformationsContainer = $container;

        return $this;
    }
}
