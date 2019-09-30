<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT {2019}
 */

namespace soIT\LaravelSeeders\Executors\Traits;

use soIT\LaravelSeeders\Containers\DataContainer;
use soIT\LaravelSeeders\Executors\ExecutorInterface;
use soIT\LaravelSeeders\Sources\SourceInterface;

trait HasSources
{
    /**
     * @var SourceInterface[] Array of sources
     */
    protected $sources = [];

    /**
     * Add data source for seeder
     *
     * @param SourceInterface $source Data source
     *
     * @return ExecutorInterface
     */
    public function addSource(SourceInterface $source): self
    {
        array_push($this->sources, $source);

        return $this;
    }

    /**
     * Get array with defined sources
     *
     * @return SourceInterface[] Array with sources objects
     */
    public function getSources(): array
    {
        return $this->sources;
    }

    /**
     * Return data which will be seed
     *
     * @return DataContainer
     */
    public function proceedSources(): DataContainer
    {
        $sourceData = [];

        foreach ($this->sources as $source) {
            $sourceData = array_merge($sourceData, $source->data());
        }

        return new DataContainer($sourceData);
    }
}
