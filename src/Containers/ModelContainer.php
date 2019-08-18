<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders\Containers;

use Illuminate\Database\Eloquent\Model;
use soIT\LaravelSeeders\Exceptions\NoPropertySetException;
use soIT\LaravelSeeders\Seeders\SeederInterface;

class ModelContainer
{
    /**
     * @var string Model name
     */
    private $modelName;
    /**
     * @var DataContainer
     */
    private $data;
    /**
     * @var TransformationsContainer
     */
    private $transformations;
    /**
     * @var TranslationsContainer
     */
    private $translations;
    /**
     * @var Model
     */
    private $model;
    /**
     * @var ModelContainer[]
     */
    private $seeders = [];

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
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Return set model name
     *
     * @return string
     */
    public function getModelName(): string
    {
        return $this->modelName;
    }

    /**
     * @return ModelContainer[] Array of defined seeders
     */
    public function getSeeders(): array
    {
        return $this->seeders;
    }

    /**
     * Prepare all model data to save
     * @throws NoPropertySetException
     */
    public function prepare(): self
    {
        if (is_null($this->data)) {
            throw new NoPropertySetException("Data must be set for proceeding model container");
        }

        $this->model = $this->initModel();
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
    public function setData(DataContainer $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set seeder for property
     *
     * @param SeederInterface $seeder
     *
     * @return ModelContainer
     */
    public function setSeeder(SeederInterface $seeder): self
    {
        array_push($this->seeders, $seeder);
        return $this;
    }

    /**
     * Set data transformations
     *
     * @param TransformationsContainer $transformations
     *
     * @return ModelContainer
     */
    public function setTransformations(?TransformationsContainer $transformations): self
    {
        $this->transformations = $transformations ?? new TransformationsContainer();

        return $this;
    }

    /**
     * Set property translations container
     *
     * @param TranslationsContainer $translations
     * @return ModelContainer
     */
    public function setTranslations(?TranslationsContainer $translations): self
    {
        $this->translations = $translations ?? new TranslationsContainer();

        return $this;
    }

    /**
     * Init model instance
     */
    protected function initModel(): Model
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

            if ($propertyValue instanceof SeederInterface) {
                $this->setSeeder($propertyValue);
            } elseif (is_string($propertyValue)) {
                $this->model->{$propertyName} = $propertyValue;
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
    private function getTargetProperty(string $property): string
    {
        if (is_null($this->translations)) {
            return $property;
        }

        return $this->translations->get($property) ?? $property;
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
