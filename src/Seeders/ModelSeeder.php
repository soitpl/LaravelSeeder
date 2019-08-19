<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */
namespace soIT\LaravelSeeders\Seeders;

use Illuminate\Database\Eloquent\Model;
use soIT\LaravelSeeders\Containers\ModelContainer;

class ModelSeeder extends SeederAbstract
{
    use SeederTransformationsTrait, SeederTranslationsTrait;

    /**
     * @var ModelContainer Instance of ModelMaker class
     */
    private $modelContainer;
    /**
     * @var string Target model name
     */
    protected $modelName;
    /**
     * @var object Model object
     */
    protected $model;

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
    public function getName(): string
    {
        return $this->modelName;
    }

    /**
     * Create and save new model in database
     *
     * @throws \soIT\LaravelSeeders\Exceptions\NoPropertySetException
     */
    public function save()
    {
        $container = $this->_initModelContainer();
        $container->setData($this->getData());
        $container->prepare();
        $model = $container->getModel();

        $model->save();

        $this->_executeSeeders($model, $container->getSeeders());
    }

    /**
     * Init instance of model maker
     */
    protected function _initModelContainer(): ModelContainer
    {
        $this->modelContainer = new ModelContainer($this->modelName);
        $this->modelContainer->setTransformations($this->getTransformations());
        $this->modelContainer->setTranslations($this->getTranslations());

        return $this->modelContainer;
    }

    /**
     * @param Model $model
     * @param array $seeders
     */
    protected function _executeSeeders(Model $model, array $seeders): void
    {
        if (count($seeders)) {
            foreach ($seeders as $seeder) {
                $seeder->setParentModel($model);
                $seeder->save();
            }
        }
    }
}
