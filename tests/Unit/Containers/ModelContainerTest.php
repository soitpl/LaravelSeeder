<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Containers;

use Illuminate\Database\Eloquent\Model;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Exceptions\NoPropertySetException;
use soIT\LaravelSeeders\Seeders\SeederAbstract;
use soIT\LaravelSeeders\Seeders\SeederInterface;


class ModelContainerTest extends TestCase
{
    private const MODEL_NAME = 'soIT\LaravelSeeders\Containers\TestModel';

    public function testSetGetSeeder()
    {
        $container = new ModelContainer(self::MODEL_NAME);

        for ($i=1; $i < 5; $i++) {
            $seederMockName = \Mockery::mock(SeederAbstract::class);
            $seederMockName->shouldReceive('testMethod')->andReturn($i);

            $container->setSeeder($seederMockName);
            $seeders = $container->getSeeders();

            $this->assertCount($i, $seeders);
            $this->assertEquals($seederMockName, $seeders[$i-1]);
            $this->assertEquals($i, $seeders[$i-1]->testMethod());
        }
    }

    public function testSetSeederWithNoSeeder()
    {
        $container = new ModelContainer(self::MODEL_NAME);
        $this->expectException(\TypeError::class);
        $container->setSeeder(new \stdClass());
    }

    /**
     * @throws NoPropertySetException
     */
    public function testGetModel()
    {
        /**
         * @var ModelContainer|Mock $modelContainerMock
         */
        $modelContainerMock = \Mockery::mock(ModelContainer::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mockModel = \Mockery::mock(self::MODEL_NAME, Model::class);
        $modelContainerMock->shouldReceive('initModel')->andReturn($mockModel);
        $modelContainerMock->shouldReceive('proceedData')->once();

        $modelContainerMock->setData(new DataContainer([]));
        $modelContainerMock->prepare();

        $model = $modelContainerMock->getModel();
        $this->assertEquals($model, $mockModel);
        $this->assertInstanceOf(Model::class, $model);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws NoPropertySetException
     */
    public function testPrepare()
    {
        $data = new DataContainer(['prop_1'=>'value_1', 'prop_2'=> "value_2"]);

        $container = new ModelContainer(TestModel::class);
        $container->setData($data);

        $container->prepare();
        $this->assertInstanceOf(TestModel::class, $container->getModel());
        $this->assertEquals($container->getData(), $data);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws NoPropertySetException
     */
    public function testPrepareWithoutData()
    {

        $container = new ModelContainer(TestModel::class);
        $this->expectException(NoPropertySetException::class);

        $container->prepare();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testPrepareWithTranslations()
    {
        $translationsMock = \Mockery::mock(TranslationsContainer::class);
        $translationsMock->shouldReceive('get')->with('prop_1')->andReturn('prop_1_t');
        $translationsMock->shouldReceive('get')->with('prop_2')->andReturn(null);

        $container = new ModelContainer(TestModel::class);
        $container->setData(new DataContainer(['prop_1'=>'value_1', 'prop_2'=> "value_2"]));
        $container->setTranslations($translationsMock);

        $container->prepare();
        $this->assertInstanceOf(TestModel::class, $container->getModel());
        $this->assertEquals("value_1", $container->getModel()->prop_1_t);
        $this->assertEquals("value_2", $container->getModel()->prop_2);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws NoPropertySetException
     */
    public function testPrepareWithTransformations()
    {
        $transformationsMock = \Mockery::mock(TransformationsContainer::class);
        $transformationsMock->shouldReceive('getValue')->with('prop_1', 'value_1')->andReturn('value_1_t');
        $transformationsMock->shouldReceive('getValue')->with('prop_2', 'value_2')->andReturn(\Mockery::mock(SeederInterface::class));

        $container = new ModelContainer(TestModel::class);
        $container->setData(new DataContainer(['prop_1'=>'value_1', 'prop_2'=> "value_2"]));
        $container->setTransformations($transformationsMock);

        $container->prepare();
        $this->assertInstanceOf(TestModel::class, $container->getModel());
        $this->assertEquals("value_1_t", $container->getModel()->prop_1);

        $seeders = $container->getSeeders();
        $this->assertCount(1, $seeders);
        $this->assertInstanceOf(SeederInterface::class, $seeders[0]);

    }

    public function testSetData()
    {
        $container = new ModelContainer(self::MODEL_NAME);
        $dataContainer = new DataContainer();

        for ($i=1; $i < 5; $i++) {
            $dataContainer[] = $i;

            $container->setData($dataContainer);
            $data = $container->getData();

            $this->assertCount($i, $data);
            $this->assertEquals($dataContainer, $data);
            $this->assertEquals($i, $data[$i-1]);
        }
    }

    public function testGetDataWithNoiDataSet()
    {
        $container = new ModelContainer(self::MODEL_NAME);
        $data = $container->getData();

        $this->assertInstanceOf(DataContainer::class, $data);
        $this->assertEquals(new DataContainer(), $data);
    }

    public function testSetTransformations()
    {
        $transformationMock = \Mockery::mock(TransformationsContainer::class);
        $container = new ModelContainer(self::MODEL_NAME);
        $retContainer = $container->setTransformations($transformationMock);

        $this->assertEquals($retContainer, $container);

        $this->expectException(\TypeError::class);
        $container->setTransformations(new \stdClass());
    }

    public function testSetTranslations()
    {
        $translationsMock = \Mockery::mock(TranslationsContainer::class);
        $container = new ModelContainer(self::MODEL_NAME);
        $retContainer = $container->setTranslations($translationsMock);

        $this->assertEquals($retContainer, $container);

        $this->expectException(\TypeError::class);
        $container->setTranslations(new \stdClass());
    }

    public function testConstructor()
    {
        $instance = new ModelContainer(self::MODEL_NAME);
        $this->assertEquals(self::MODEL_NAME, $instance->getModelName());

        $this->expectException(\TypeError::class);
        new ModelContainer(new \StdClass());
    }
}

class TestModel extends Model{

}
