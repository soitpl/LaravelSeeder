<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Seeders;

use soIT\LaravelSeeder\Contracts\SeederInterface;
use soIT\LaravelSeeder\Containers\DataContainer;
use soIT\LaravelSeeder\Containers\TransformationsContainer;

abstract class SeederAbstract implements SeederInterface
{
    abstract public function setTransformations(TransformationsContainer $transformations);
    abstract public function save();
    
    /**
     * @var DataContainer $data Data to seed
     */
    protected $data;

    /**
     * @var int Type of behavior on duplicated entry
     */
    protected $duplicated = null;

    /**
     * Set behavior on duplicated entry
     *
     * @param int $duplicated
     *
     * @return SeederAbstract
     */
    public function onDuplicate(int $duplicated): SeederAbstract
    {
        $this->duplicated = $duplicated;

        return $this;
    }

    /**
     * Assign data to model
     *
     * @param DataContainer $data
     *
     * @return SeederInterface
     */
    public function setData(DataContainer $data): SeederAbstract
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return DataContainer
     */
    public function getData(): DataContainer
    {
        return $this->data;
    }
}
