<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Executors;

use soIT\LaravelSeeder\Containers\DataContainer;
use soIT\LaravelSeeder\Containers\TransformationsContainer;
use soIT\LaravelSeeder\Contracts\ExecutorInterface;
use soIT\LaravelSeeder\Executors\Traits\HasAdditionalProperties;
use soIT\LaravelSeeder\Executors\Traits\HasPropertiesTransformation;
use soIT\LaravelSeeder\Executors\Traits\HasNamingStrategy;
use soIT\LaravelSeeder\Executors\Traits\HasSources;
use soIT\LaravelSeeder\Seeders\TableSeeder;

/**
 * Class TableExecutor
 *
 * @codeCoverageIgnore
 */
class TableExecutor extends ExecutorAbstract implements ExecutorInterface
{
    use HasAdditionalProperties;
    use HasPropertiesTransformation;
    use HasNamingStrategy;
    use HasSources;

    /**
     * ModelExecutor constructor.
     *
     * @param TableSeeder $table Model assigned to executor
     * @param TransformationsContainer $transformations Mapping container with columns mapping info.
     *

     */
    public function __construct(TableSeeder $table, TransformationsContainer $transformations = null)
    {
        $this->setSeeder($table)
             ->setTransformations($transformations);
    }

    /**
     * Set behavior on duplicated entry
     *
     * @param int $duplicated
     * @param array $uniqueKeys
     *
     * @return TableExecutor
     */
    public function onDuplicate(int $duplicated, array $uniqueKeys = []):ExecutorInterface
    {
        $this->getSeeder()
             ->onDuplicate($duplicated)
             ->setUniqueKeys($uniqueKeys);

        return $this;
    }

    /**
     * Function execute seed to database
     *
     * @param DataContainer $data
     *
     * @return bool
     */
    public function execute(DataContainer $data):bool
    {
        $this->getSeeder()
             ->setTransformations($this->getTransformations())
             ->setTranslations($this->getNamingStrategy())
             ->setProperties($this->getAdditionalProperties());

        return parent::execute($data);
    }
}
