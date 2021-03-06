<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeder\Seeders;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use soIT\LaravelSeeder\Containers\DataContainer;
use soIT\LaravelSeeders\Exceptions\WrongAttributeException;

class AttachModelSeeder extends ModelSeeder
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
     * @param DataContainer $data
     *
     * @return SeederAbstract
     * @throws WrongAttributeException
     */
    public function setData(DataContainer $data): SeederAbstract
    {
        $items = new DataContainer();

        foreach ($data as $ident => $value) {
            if (!is_string($ident)) {
                throw new WrongAttributeException(
                    "Wrong input attribute for attach seeder. Should be have search string as a property index"
                );
            }

            $items = $items->merge($this->modelName::where($ident, $value)->get());
        }

        $this->data = $items;

        return $this;
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
     * @throws Exception
     */
    public function save(): void
    {
        $this->prepareContainers($this->data);
        $this->saveContainers();
    }

    /**
     * Prepare all models containers for seed
     *
     * @param DataContainer $data
     *
     */
    private function prepareContainers(DataContainer $data): void
    {
        $this->containers->push($this->initModelContainer()->setData($data));
    }

    /**
     * Save prepared containers
     * @throws Exception
     */
    private function saveContainers(): void
    {
        $this->attachModels();
    }

    /**
     * Save models in sequential
     * Method should be used if models have nested models to save
     * @throws Exception
     */
    private function attachModels(): void
    {
        foreach ($this->containers as $container) {
            /**
             * @var Collection $data
             */
            $data = $container->getData();

            $relation = $this->getRelation($this->getRelationName($data->first()));
            foreach ($data as $model) {
                $relation->attach($model, []);
            }
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

    /**
     * Check is relations exists
     *
     * @param string $relationName
     *
     * @return MorphToMany
     * @throws Exception
     */
    private function getRelation(string $relationName): MorphToMany
    {
        if ($relation = $this->parentModel->$relationName()) {
            return $relation;
        }

        throw new Exception(sprintf("No relation %s found", $relation));
    }
}
