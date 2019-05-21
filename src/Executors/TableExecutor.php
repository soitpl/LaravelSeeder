<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
 */
namespace soIT\LaravelSeeders\Executors;

use soIT\LaravelSeeders\Executors\Features\TransformationPropertiesTrait;
use soIT\LaravelSeeders\Executors\Features\TranslationPropertiesTrait;
use soIT\LaravelSeeders\Seeders\TableSeeder;
use soIT\LaravelSeeders\Containers\TransformationsContainer;
use soIT\LaravelSeeders\Containers\DataContainer;
use soIT\LaravelSeeders\Sources\SourceInterface;

/**
 * Class TableExecutor
 *
 * @property TableSeeder $seeder
 * @method TableExecutor addSource(SourceInterface $source)
 */
class TableExecutor extends ExecutorAbstract implements TableExecutorInterface
{
    use TranslationPropertiesTrait, TransformationPropertiesTrait;

    /**
     * ModelExecutor constructor.
     *
     * @param string $table Model assigned to executor
     * @param TransformationsContainer|null $transformations Mapping container with columns mapping info.
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

        return parent::execute($data);
    }
}
