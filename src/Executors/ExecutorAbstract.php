<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders\Executors;

use soIT\LaravelSeeders\Seeders\SeederAbstract;
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
     * @param array $uniqueKeys Array of unique kays which will be compare to define duplicated records
     *
     * @return ExecutorAbstract
     */
    public function onDuplicate(int $duplicated, array $uniqueKeys): ExecutorAbstract
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
