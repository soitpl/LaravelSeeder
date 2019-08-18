<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
 */
namespace soIT\LaravelSeeders\Executors;

use soIT\LaravelSeeders\Seeders\SeederAbstract;
use soIT\LaravelSeeders\Sources\SourceInterface;
use soIT\LaravelSeeders\Containers\DataContainer;

abstract class ExecutorAbstract implements ExecutorInterface
{
    /**
     * @var DataContainer Data array
     */
    protected $data;

    /**
     * @var string Set if source file has only one col. This attribute contains column name
     */
    protected $oneCol;

    /**
     * @var SeederAbstract Target object Model or Table
     */
    protected $seeder;

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
    public function addSource(SourceInterface $source): ExecutorInterface
    {
        array_push($this->sources, $source);
        return $this;
    }

    /**
     * Execute data items
     *
     * @param DataContainer $data
     *
     * @return bool
     */
    public function execute(DataContainer $data): bool
    {
        if ($this->oneCol) {
            $data = $this->packStringToDataContainer($data);
        }

        foreach ($data as $item) {
            $this->executeTarget($item);
        }
        return true;
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
     * Get seeder
     *
     * @return SeederAbstract|null
     */
    public function getSeeder(): ?SeederAbstract
    {
        return $this->seeder;
    }

    /**
     * Set seeder
     *
     * @param SeederAbstract $seeder Seeder class
     *
     * @return ExecutorAbstract
     */
    public function setSeeder(SeederAbstract $seeder): self
    {
        $this->seeder = $seeder;
        return $this;
    }

    /**
     * Set behavior on duplicated entry
     *
     * @param int $duplicated

     *
     * @return ExecutorAbstract
     */
    public function onDuplicate(int $duplicated): ExecutorAbstract
    {
        $this->getSeeder()->onDuplicate($duplicated);

        return $this;
    }

    /**
     * Set work with only one column in source file.
     *
     * @param string $columnName Define where source data should be add
     *
     * @return ExecutorAbstract
     */
    public function oneCol(string $columnName): ExecutorAbstract
    {
        $this->oneCol = $columnName;

        return $this;
    }

    /**
     * Make data seed
     */
    public function seed(): bool
    {
        return $this->execute($this->proceedSources());
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

    /**
     * @param DataContainer $item
     *
     * @return mixed
     */
    protected function executeTarget(DataContainer $item)
    {
        return $this->seeder->setData($item)->save();
    }

    /**
     * Create DataContainer for OneCol mode
     *
     * @param DataContainer $data
     *
     * @return DataContainer
     */
    protected function packStringToDataContainer(DataContainer $data): DataContainer
    {
        foreach ($data as $k => $item) {
            $data[$k] = new DataContainer([$this->oneCol => $item]);
        }
        return $data;
    }
}
