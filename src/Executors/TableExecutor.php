<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders\Executors;

use soIT\LaravelSeeders\Containers\TransformationsContainer;
use soIT\LaravelSeeders\Executors\Traits\HasAdditionalProperties;
use soIT\LaravelSeeders\Executors\Traits\HasPropertiesTranslation;
use soIT\LaravelSeeders\Executors\Traits\HasSources;
use soIT\LaravelSeeders\Executors\Traits\HasPropertiesTransformation;
use soIT\LaravelSeeders\Seeders\TableSeeder;
use soIT\LaravelSeeders\Containers\DataContainer;
use soIT\LaravelSeeders\Sources\SourceInterface;

/**
 * Class TableExecutor
 *
 * @property TableSeeder $seeder
 * @method TableExecutor addSource(SourceInterface $source)
 */
class TableExecutor extends ExecutorAbstract implements ExecutorInterface
{
    use HasSources, HasPropertiesTranslation, HasPropertiesTransformation, HasAdditionalProperties;

    /**
     * ModelExecutor constructor.
     *
     * @param string $table Model assigned to executor
     * @param AdditionalProperiesConatiner|null $transformations Mapping container with columns mapping info.
     *
     * @throws \soIT\LaravelSeeders\Exceptions\SeedTargetFoundException
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
     *
     * @param array $uniqueKeys
     *
     * @return TableExecutor
     */
    public function onDuplicate(int $duplicated, array $uniqueKeys=[]): ExecutorAbstract
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
    public function execute(DataContainer $data): bool
    {
        $this->seeder->setTransformations($this->getTransformations());
        $this->seeder->setTranslations($this->getTranslations());
        $this->seeder->setProperties($this->getAdditionalProperties());

        return parent::execute($data);
    }
}
