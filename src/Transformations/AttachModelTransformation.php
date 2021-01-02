<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Transformations;

use soIT\LaravelSeeder\Contracts\SeederInterface;
use soIT\LaravelSeeder\Seeders\AttachModelSeeder;
use soIT\LaravelSeeder\Containers\TransformationsContainer;
use soIT\LaravelSeeders\Exceptions\WrongAttributeException;

class AttachModelTransformation implements TransformationsInterface
{
    private string $modelName;

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
     * @return SeederInterface
     * @throws WrongAttributeException
     */
    public function transform($propertyValue):SeederInterface
    {
        return (new AttachModelSeeder($this->modelName))
            //->setTransformations($this->transformationsContainer)
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
        $propertyName = $property;

        return $this;
    }
}
