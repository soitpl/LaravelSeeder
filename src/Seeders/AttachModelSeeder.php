<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Seeders;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use ReflectionClass;
use soIT\LaravelSeeder\Containers\DataContainer;
use soIT\LaravelSeeders\Exceptions\WrongAttributeException;

class AttachModelSeeder extends ModelSeeder
{
    protected Model $parentModel;
    private Collection $containers;
    private ?string $relation;

    public function __construct(string $modelName, ?string $relation = null)
    {
        $this->containers = new Collection();

        parent::__construct($modelName);

        $this->setRelation($relation);
    }

    /**
     * @param DataContainer $data
     *
     * @return SeederAbstract
     * @throws WrongAttributeException
     */
    public function setData(DataContainer $data):SeederAbstract
    {
        $items = new DataContainer();

        foreach ($data as $ident => $value) {
            if (!is_string($ident)) {
                throw new WrongAttributeException(
                    "Wrong input attribute for attach seeder. Should be have search string as a property index"
                );
            }

            $items = $items->merge($this->createModel()->newQuery()->where($ident, $value)->get());
        }

        $this->data = $items;

        return $this;
    }

    /**
     * Create and save new model in database
     *
     * @return void
     * @throws Exception
     */
    public function save():void
    {
        $this->prepareContainers($this->data);
        $this->saveContainers();
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
     * @param string|null $relation
     *
     * @return AttachModelSeeder
     */
    public function setRelation(?string $relation):self
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * Prepare all models containers for seed
     *
     * @param DataContainer $data
     *
     */
    private function prepareContainers(DataContainer $data):void
    {
        $this->containers->push($this->initModelContainer()->setData($data));
    }

    /**
     * Save prepared containers
     * @throws Exception
     */
    private function saveContainers():void
    {
        $this->attachModels();
    }

    /**
     * Save models in sequential
     * Method should be used if models have nested models to save
     * @throws Exception
     */
    private function attachModels():void
    {
        foreach ($this->containers as $container) {
            /**
             * @var Collection $data
             */
            $data = $container->getData();

            $modelRelation = $this->getRelation($this->relation ?? $this->getRelationName($data->first()));

            foreach ($data as $model) {
                $this->assignModel($modelRelation, $model);
            }
        }
    }

    /**
     * Get parent relation method name
     *
     * @param Model $model Model to search relation method
     *
     * @return string Method name
     * @throws Exception
     */
    private function getRelationName(Model $model):string
    {
        $className = strtolower(class_basename($model));
        $reflection = new ReflectionClass($this->parentModel);

        if ($reflection->hasMethod($className)) {
            return $className;
        } else {
            $className_ = str_replace('model', '', $className).'s';

            if ($reflection->hasMethod($className_)) {
                return $className_;
            } else {
                $className_ = $className.'s';

                if ($reflection->hasMethod($className_)) {
                    return $className_;
                }
            }
        }

        throw new Exception(sprintf("No relation %s found", $className));
    }

    /**
     * Check is relations exists
     *
     * @param string $relationName
     *
     * @return MorphToMany
     * @throws Exception
     */
    private function getRelation(string $relationName):object
    {
        return $this->parentModel->$relationName();
    }

    private function assignModel(Relation $relation, Model $model):void
    {
        if ($relation instanceof MorphToMany) {
            $relation->attach($model, []);
        } else {
            if ($relation instanceof BelongsTo) {
                $relation->associate($model)->save();
            }
        }
    }

    private function createModel():Model
    {
        return new $this->modelName;
    }
}
