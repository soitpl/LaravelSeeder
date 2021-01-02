<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Executors;

use soIT\LaravelSeeder\Containers\DataContainer;
use soIT\LaravelSeeder\Contracts\ExecutorInterface;
use soIT\LaravelSeeder\Executors\Traits\HasPropertiesTransformation;
use soIT\LaravelSeeder\Containers\TransformationsContainer;
use soIT\LaravelSeeder\Executors\Traits\HasAdditionalProperties;
use soIT\LaravelSeeder\Executors\Traits\HasNamingStrategy;
use soIT\LaravelSeeder\Executors\Traits\HasSources;
use soIT\LaravelSeeder\Seeders\ModelSeeder;

use soIT\LaravelSeeder\Transformations\AttachModelTransformation;
use soIT\LaravelSeeder\Transformations\ModelTransformation;

/**
 * Class ModelExecutor
 * @package soIT\LaravelSeeders\Executors
 *
 * @property ModelSeeder $seeder
 * @codeCoverageIgnore
 */
class ModelExecutor extends ExecutorAbstract implements ExecutorInterface
{
    use HasAdditionalProperties;
    use HasPropertiesTransformation;
    use HasNamingStrategy;
    use HasSources;

    /**
     * ModelExecutor constructor.
     *
     * @param ModelSeeder $model Model assigned to executor
     * @param TransformationsContainer $transformations Mapping container with columns mapping info.
     */
    public function __construct(ModelSeeder $model, TransformationsContainer $transformations = null)
    {
        $this->setSeeder($model);
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
    public function assignModel(string $propertyName, string $model):self
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
    public function setPropertyRelationModel(string $propertyName, string $model):self
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
    public function execute(DataContainer $data):bool
    {
        $this->getSeeder()
             ->setTransformations($this->getTransformations())
             ->setTranslations($this->getNamingStrategy());

        return parent::execute($data);
    }
}
