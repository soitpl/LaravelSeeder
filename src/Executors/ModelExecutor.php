<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders\Executors;

use soIT\LaravelSeeders\Containers\TransformationsContainer;
use soIT\LaravelSeeders\Containers\DataContainer;
use soIT\LaravelSeeders\Executors\Traits\HasAdditionalProperties;
use soIT\LaravelSeeders\Executors\Traits\HasPropertiesTranslation;
use soIT\LaravelSeeders\Executors\Traits\HasSources;
use soIT\LaravelSeeders\Executors\Traits\HasPropertiesTransformation;
use soIT\LaravelSeeders\Seeders\ModelSeeder;
use soIT\LaravelSeeders\Transformations\ModelTransformation;
use soIT\LaravelSeeders\Transformations\AttachModelTransformation;

/**
 * Class ModelExecutor
 * @package soIT\LaravelSeeders\Executors
 *
 * @property ModelSeeder $seeder
 */
class ModelExecutor extends ExecutorAbstract implements ExecutorInterface
{
    use HasSources, HasPropertiesTranslation, HasPropertiesTransformation, HasAdditionalProperties;

    /**
     * ModelExecutor constructor.
     *
     * @param string $model Model assigned to executor
     * @param TransformationsContainer $transformations Mapping container with columns mapping info.
     */
    public function __construct(string $model, TransformationsContainer $transformations = null)
    {
        $this->setSeeder(new ModelSeeder($model));
        $this->setTransformations($transformations);
    }

    /**
     * Assign model to property.
     * If property will be find new model will be created and added to databse
     *
     * @param string $propertyName
     * @param string $model
     *
     * @return ModelExecutor
     */
    public function assignModel(string $propertyName, string $model): self
    {
        $this->getTransformations()->assign($propertyName, new ModelTransformation($model));

        return $this;
    }

    /**
     * Assign model to property.
     * If property will be find new model will be created and added to databse
     *
     * @param string $propertyName
     * @param string $model
     *
     * @return ModelExecutor
     */
    public function attachTo(string $propertyName, string $model): self
    {
        $this->getTransformations()->assign($propertyName, new AttachModelTransformation($model));

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
