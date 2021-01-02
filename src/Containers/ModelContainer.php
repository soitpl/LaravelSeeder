<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Containers;

use Illuminate\Database\Eloquent\Model;
use soIT\LaravelSeeder\Contracts\SeederInterface;
use soIT\LaravelSeeder\Exceptions\NoPropertySetException;
use soIT\LaravelSeeders\Traits\HasTableColumns;

class ModelContainer
{
    use HasTableColumns;

    /**
     * @var string Model name
     */
    private string $modelName;
    /**
     * @var DataContainer|null
     */
    private ?DataContainer $data = null;
    /**
     * @var TransformationsContainer
     */
    private ?TransformationsContainer $transformations = null;
    /**
     * @var NamingStrategyContainer
     */
    private ?NamingStrategyContainer $namingStrategy = null;
    /**
     * @var Model
     */
    private Model $model;
    /**
     * @var ModelContainer[]
     */
    private array $seeders = [];

    /**
     * ModelMaker constructor.
     *
     * @param string $modelName
     */
    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;
    }

    /**
     * Get created model
     *
     * @return Model
     */
    public function getModel():Model
    {
        return $this->model;
    }

    /**
     * Return set model name
     *
     * @return string
     */
    public function getModelName():string
    {
        return $this->modelName;
    }

    /**
     * @return ModelContainer[] Array of defined seeders
     */
    public function getSeeders():array
    {
        return $this->seeders;
    }

    /**
     * Set seeder for property
     *
     * @param SeederInterface $seeder
     *
     * @return ModelContainer
     */
    public function setSeeder(SeederInterface $seeder):self
    {
        array_push($this->seeders, $seeder);

        return $this;
    }

    /**
     * Prepare all model data to save
     * @throws NoPropertySetException
     */
    public function prepare():self
    {
        if (is_null($this->data)) {
            throw new NoPropertySetException("Data must be set for proceeding model container");
        }

        $this->model = $this->createModel();
        $this->setColumns($this->model->getTable());
        $this->proceedData();

        return $this;
    }

    /**
     * Get data to save
     *
     * @return DataContainer
     */
    public function getData():DataContainer
    {
        return $this->data ?? new DataContainer();
    }

    /**
     * Set data to save
     *
     * @param DataContainer $data
     *
     * @return ModelContainer
     */
    public function setData(DataContainer $data):self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set data transformations
     *
     * @param TransformationsContainer|null $transformations
     *
     * @return ModelContainer
     */
    public function setTransformations(?TransformationsContainer $transformations):self
    {
        $this->transformations = $transformations ?? new TransformationsContainer();

        return $this;
    }

    /**
     * Set property translations container
     *
     * @param NamingStrategyContainer $namingStrategy
     *
     * @return ModelContainer
     */
    public function setNamingStrategy(?NamingStrategyContainer $namingStrategy):self
    {
        $this->namingStrategy = $namingStrategy ?? new NamingStrategyContainer();

        return $this;
    }

    /**
     * Init model instance
     */
    protected function createModel():Model
    {
        return new $this->modelName();
    }

    /**
     * Proceed data assigned to model
     */
    protected function proceedData()
    {
        foreach ($this->data as $property => $value) {
            $propertyValue = $this->getTargetTransformation($property, $value);
            $propertyName = $this->getTargetProperty($property);

            if ($propertyValue instanceof \soIT\LaravelSeeder\Contracts\SeederInterface) {
                $this->setSeeder($propertyValue);
            } elseif (is_string($propertyValue) && $this->isColumnExistInTable($propertyName)) {
                $this->model->setAttribute($propertyName, $propertyValue);
            }
        }
    }

    /**
     * Get real target property name
     *
     * @param string $property
     *
     * @return string
     */
    private function getTargetProperty(string $property):string
    {
        if (is_null($this->namingStrategy)) {
            return $property;
        }

        return $this->namingStrategy->get($property) ?? $property;
    }

    /**
     * Get real target property name
     *
     * @param string $property
     *
     * @param $value
     *
     * @return string
     */
    private function getTargetTransformation(string $property, $value)
    {
        if (is_null($this->transformations)) {
            return $value;
        }

        return $this->transformations->getValue($property, $value) ?? $value;
    }
}
