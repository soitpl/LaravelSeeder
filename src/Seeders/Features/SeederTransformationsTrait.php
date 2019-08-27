<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders\Seeders\Features;

use soIT\LaravelSeeders\Containers\TransformationsContainer;

trait SeederTransformationsTrait
{
    /**
     * @var TransformationsContainer Mapping info
     */
    protected $transformations;

    /**
     * Transformations
     *
     * @return TransformationsContainer
     */
    public function getTransformations(): TransformationsContainer
    {
        return $this->transformations ?? new TransformationsContainer();
    }

    /**
     * Mapping
     *
     * @param TransformationsContainer $transformations
     *
     * @return SeederTransformationsTrait
     */
    public function setTransformations(?TransformationsContainer $transformations)
    {
        $this->transformations = $transformations;

        return $this;
    }
}
