<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Transformations;

use soIT\LaravelSeeders\Containers\TransformationsContainer;
use soIT\LaravelSeeders\Seeders\AttachModelSeeder;
use soIT\LaravelSeeders\Seeders\SeederAbstract;
use soIT\LaravelSeeders\Transformations\TransformationsInterface;

class AttachModelTransformation implements TransformationsInterface
{
    /**
     * @var string Model name
     */
    private string $modelName;
    /**
     * @var string
     */
    private string $propertyName;

    /**
     * @var TransformationsContainer
     */
    private TransformationsContainer $transformationsContainer;

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
    public function transform($propertyValue):SeederAbstract
    {
        return (new AttachModelSeeder($this->modelName))
            ->setTransformations($this->transformationsContainer)
            ->setData($propertyValue);
    }

    /**
     * @param TransformationsContainer $container
     *
     * @return TransformationsInterface
     */
    public function setTransformationsContainer(TransformationsContainer $container):TransformationsInterface
    {
        $this->transformationsContainer = $container;

        return $this;
    }

    /**
     * @param string $property
     *
     * @return TransformationsInterface
     */
    public function setPropertyName(string $property):TransformationsInterface
    {
        $this->propertyName = $property;

        return $this;
    }
}
