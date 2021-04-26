<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Seeders;

use Illuminate\Database\Eloquent\Model;
use soIT\LaravelSeeder\Containers\ModelContainer;
use soIT\LaravelSeeder\Exceptions\NoPropertySetException;
use soIT\LaravelSeeder\Seeders\Traits\HasTransformations;
use soIT\LaravelSeeder\Seeders\Traits\SeederAdditionalPropertiesTrait;
use soIT\LaravelSeeder\Seeders\Traits\SeederTranslationsTrait;

class ModelSeeder extends SeederAbstract implements \soIT\LaravelSeeder\Contracts\SeederInterface
{
    use SeederAdditionalPropertiesTrait;
    use HasTransformations;
    use SeederTranslationsTrait;

    /**
     * @var string Target model name
     */
    protected string $modelName;
    /**
     * @var object Model object
     */
    protected object $model;

    /**
     * ModelDispatcher constructor.
     *
     * @param string $modelName
     */
    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;
    }

    /**
     * Get model name
     *
     * @return string
     */
    public function getName():string
    {
        return $this->modelName;
    }

    /**
     * Create and save new model in database
     *
     * @throws NoPropertySetException
     */
    public function save():void
    {

        $container = $this->initModelContainer();
        $container->setData($this->getData());
        $container->prepare();

        $model = $container->getModel();

        $model->save();

        $this->executeSeeders($model, $container->getSeeders());
    }

    /**
     * Init instance of model maker
     */
    protected function initModelContainer():ModelContainer
    {
        $modelContainer = new ModelContainer($this->modelName);
        return $modelContainer->setTransformations($this->getTransformations())
                              ->setNamingStrategy($this->getTranslations());
    }

    /**
     * @param Model $model
     * @param array $seeders
     */
    protected function executeSeeders(Model $model, array $seeders):void
    {
        if (count($seeders)) {
            foreach ($seeders as $seeder) {
                $seeder->setParentModel($model);
                $seeder->save();
            }
        }
    }
}
