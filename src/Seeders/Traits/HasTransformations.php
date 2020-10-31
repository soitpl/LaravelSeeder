<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Seeders\Traits;

use soIT\LaravelSeeder\Containers\TransformationsContainer;

trait HasTransformations
{
    /**
     * @var TransformationsContainer Mapping info
     */
    protected ?TransformationsContainer $transformations = null;

    /**
     * Transformations
     *
     * @return TransformationsContainer
     */
    public function getTransformations():TransformationsContainer
    {
        return $this->transformations ?? new TransformationsContainer();
    }

    /**
     * Mapping
     *
     * @param TransformationsContainer $transformations
     *
     * @return HasTransformations
     */
    public function setTransformations(?TransformationsContainer $transformations)
    {
        $this->transformations = $transformations;

        return $this;
    }
}
