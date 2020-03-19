<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Executors;

use soIT\LaravelSeeder\Exceptions\SeedTargetFoundException;
use soIT\LaravelSeeders\Containers\TransformationsContainer;
use soIT\LaravelSeeders\Executors\AdditionalProperiesConatiner;
use soIT\LaravelSeeders\Executors\ExecutorInterface;
use soIT\LaravelSeeder\Executors\Traits\HasAdditionalProperties;
use soIT\LaravelSeeders\Executors\Traits\HasPropertiesTranslation;
use soIT\LaravelSeeders\Executors\Traits\HasSources;
use soIT\LaravelSeeders\Executors\Traits\HasPropertiesTransformation;
use soIT\LaravelSeeders\Seeders\TableSeeder;
use soIT\LaravelSeeders\Containers\DataContainer;


/**
 * Class TableExecutor
 *
 * @property TableSeeder $seeder
 */
class TableExecutor extends ExecutorAbstract implements ExecutorInterface
{
    use HasAdditionalProperties;
    use HasPropertiesTransformation;
    use HasPropertiesTranslation;
    use HasSources;

    /**
     * ModelExecutor constructor.
     *
     * @param string $table Model assigned to executor
     * @param TransformationsContainer $transformations Mapping container with columns mapping info.
     *
     * @throws SeedTargetFoundException
     */
    public function __construct(string $table, TransformationsContainer $transformations = null)
    {
        $this->setSeeder(new TableSeeder($table));
        $this->setTransformations($transformations);
    }

    /**
     * Set behavior on duplicated entry
     *
     * @param int $duplicated
     * @param array $uniqueKeys
     *
     * @return TableExecutor
     */
    public function onDuplicate(int $duplicated, array $uniqueKeys = []):ExecutorAbstract
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
        $this->seeder->setTransformations($this->getTransformations());
        $this->seeder->setTranslations($this->getTranslations());
        $this->seeder->setProperties($this->getAdditionalProperties());

        return parent::execute($data);
    }
}
