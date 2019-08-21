<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use soIT\LaravelSeeders\Containers\DataContainer;
use soIT\LaravelSeeders\Containers\ModelContainer;
use soIT\LaravelSeeders\Exceptions\NoPropertySetException;

class RelationModelSeeder extends ModelSeeder
{
    /**
     * @var Collection Collection with ModelContainer instances for models to save
     */
    private $containers;

    /**
     * @var Model Parent model instance
     */
    protected $parentModel;

    public function __construct(string $modelName)
    {
        $this->containers = new Collection();

        parent::__construct($modelName);
    }

    /**
     * Set parent module instance
     *
     * @param Model $model
     */
    public function setParentModel(Model $model)
    {
        $this->parentModel = $model;
    }

    /**
     * Create and save new model in database
     *
     * @return void
     * @throws NoPropertySetException
     */
    public function save()
    {
        $this->prepareContainers($this->data);
        $this->saveContainers();
    }

    /**
     * Prepare all models containers for seed
     *
     * @param DataContainer $data
     *
     * @throws NoPropertySetException
     */
    private function prepareContainers(DataContainer $data): void
    {
        foreach ($data as $item) {
            $this->containers->push(
                $this->initModelContainer()
                    ->setData($item)
                    ->prepare()
            );
        }
    }

    /**
     * Save prepared containers
     */
    private function saveContainers(): void
    {
        if (!$this->transformations->count()) {
            $this->saveModelsBulk();
        } else {
            $this->saveModels();
        }
    }

    /**
     * Get all models assigned to container
     *
     * @return Collection Collection of models
     */
    private function getAllModels(): Collection
    {
        return $this->containers->map(function (ModelContainer $item) {
            return $item->getModel();
        });
    }

    /**
     * Save models in one operation with saveMany() parent model method
     */
    private function saveModelsBulk()
    {
        $models = $this->getAllModels();
        $relationName = $this->getRelationName($models->first());

        $this->parentModel->$relationName()->saveMany($models);
    }

    /**
     * Save models in sequential
     * Method should be used if models have nested models to save
     */
    private function saveModels()
    {
        foreach ($this->containers as $container) {
            $container->prepare();
            $model = $container->getModel();
            $relationName = $this->getRelationName($model);

            $this->parentModel->$relationName()->save($model);

            $this->executeSeeders($model, $container->getSeeders());
        }
    }

    /**
     * Get parent relation method name
     *
     * @param Model $model Model to search relation method
     *
     * @return string Method name
     */
    private function getRelationName(Model $model): string
    {
        return strtolower(class_basename($model)) . 's';
    }
}
