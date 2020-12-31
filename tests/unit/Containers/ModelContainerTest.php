<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Tests\Unit\Containers;

use Faker\Provider\Lorem;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeder\Containers\DataContainer;
use soIT\LaravelSeeder\Containers\ModelContainer;
use soIT\LaravelSeeder\Containers\NamingStrategyContainer;
use soIT\LaravelSeeder\Containers\TransformationsContainer;
use soIT\LaravelSeeder\Exceptions\NoPropertySetException;
use soIT\LaravelSeeder\Seeders\SeederAbstract;
use soIT\LaravelSeeder\Seeders\SeederInterface;

class ModelContainerTest extends TestCase
{
    public function testSetGetSeeder()
    {
        $instance = new ModelContainer('');

        for ($i = 1; $i < 5; $i++) {
            /**
             * @var MockObject|SeederAbstract $seederMock
             */
            $seederMock = $this->getMockBuilder(SeederInterface::class)
                               ->onlyMethods(['onDuplicate', 'setData', 'setTransformations', 'save'])
                               ->addMethods(['testMethod'])
                               ->getMock();

            $seederMock->expects($this->any())->method('testMethod')->willReturn($i);

            $instance->setSeeder($seederMock);
            $seeders = $instance->getSeeders();

            $this->assertCount($i, $seeders);
            $this->assertEquals($seederMock, $seeders[$i - 1]);
            $this->assertEquals($i, $seeders[$i - 1]->testMethod());
        }
    }

    /**
     * @throws NoPropertySetException
     */
    public function testGetModel()
    {
        /**
         * @var ModelContainer|MockObject $instance
         */
        $instance = $this->getMockBuilder(ModelContainer::class)
                         ->disableOriginalConstructor()
                         ->onlyMethods(['createModel', 'proceedData', 'setColumns'])
                         ->getMock();

        $mockModel = $this->createStub(Model::class);
        $mockModel->expects($this->once())->method('getTable')->willReturn(Lorem::word());

        $instance->expects($this->once())->method('createModel')->willReturn($mockModel);
        $instance->expects($this->once())->method('setColumns');
        $instance->expects($this->once())->method('proceedData');

        $instance->setData(new DataContainer([]));
        $instance->prepare();

        $model = $instance->getModel();
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
        $data = new DataContainer(['prop_1' => 'value_1', 'prop_2' => "value_2"]);

        $mockModel = $this->createStub(Model::class);
        $mockModel->expects($this->once())->method('getTable')->willReturn(Lorem::word());

        /**
         * @var ModelContainer|MockObject $instance
         */
        $instance = $this->getMockBuilder(ModelContainer::class)
                         ->disableOriginalConstructor()
                         ->onlyMethods(['createModel', 'proceedData', 'setColumns'])
                         ->getMock();

        $instance->expects($this->once())->method('createModel')->willReturn($mockModel);
        $instance->expects($this->once())->method('setColumns');

        $instance->setData($data);

        $instance->prepare();

        $this->assertInstanceOf(Model::class, $instance->getModel());
        $this->assertEquals($instance->getData(), $data);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws NoPropertySetException
     */
    public function testPrepareWithoutData()
    {
        $instance = new ModelContainer('');

        $this->expectException(NoPropertySetException::class);
        $instance->prepare();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws NoPropertySetException
     */
    public function testPrepareWithNamingStrategy()
    {
        $ns = new NamingStrategyContainer();
        $ns->add('prop_1_t', 'prop_1');

        /**
         * @var MockObject|ModelContainer $container
         */
        $container = $this->getMockBuilder(ModelContainer::class)
                          ->onlyMethods(['setColumns', 'isColumnExistInTable'])
                          ->setConstructorArgs([TestModel::class])
                          ->getMock();

        $container->expects($this->once())->method('setColumns');
        $container->expects($this->any())->method('isColumnExistInTable')->willReturn(true);

        $container->setData(new DataContainer(['prop_1' => 'value_1', 'prop_2' => "value_2"]));
        $container->setNamingStrategy($ns);

        $container->prepare();

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
        /**
         * @var MockObject|TransformationsContainer $transformationsMock
         */
        $transformationsMock = $this->getMockBuilder(TransformationsContainer::class)
                                    ->onlyMethods(['getValue'])
                                    ->getMock();

        $transformationsMock->expects($this->exactly(2))->method('getValue')
                            ->withConsecutive(['prop_1'], ['prop_2'])
                            ->willReturn('value_1_t', $this->createMock(SeederInterface::class));

        /**
         * @var MockObject|ModelContainer $container
         */
        $container = $this->getMockBuilder(ModelContainer::class)
                          ->onlyMethods(['setColumns', 'isColumnExistInTable'])
                          ->setConstructorArgs([TestModel::class])
                          ->getMock();

        $container->expects($this->once())->method('setColumns');
        $container->expects($this->any())->method('isColumnExistInTable')->willReturn(true);

        $container->setData(new DataContainer(['prop_1' => 'value_1', 'prop_2' => "value_2"]));
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
        $container = new ModelContainer('');

        $dataContainer = new DataContainer();

        for ($i = 1; $i < 5; $i++) {
            $dataContainer[] = $i;

            $container->setData($dataContainer);
            $data = $container->getData();

            $this->assertCount($i, $data);
            $this->assertEquals($dataContainer, $data);
            $this->assertEquals($i, $data[$i - 1]);
        }
    }

    public function testGetDataWithNoiDataSet()
    {
        $container = new ModelContainer('');

        $data = $container->getData();

        $this->assertInstanceOf(DataContainer::class, $data);
        $this->assertEquals(new DataContainer(), $data);
    }

    public function testSetTransformations()
    {
        $transformationMock = new TransformationsContainer();

        $container = new ModelContainer('');
        $retContainer = $container->setTransformations($transformationMock);

        $this->assertEquals($retContainer, $container);
    }

    public function testSetTranslations()
    {
        $translationsMock = new NamingStrategyContainer();

        $container = new ModelContainer('');
        $retContainer = $container->setNamingStrategy($translationsMock);

        $this->assertEquals($retContainer, $container);
    }

    public function testConstructor()
    {
        $name = Lorem::word();

        $instance = new ModelContainer($name);
        $this->assertEquals($name, $instance->getModelName());
    }
}

class TestModel extends Model
{
    public function getTable()
    {
        return Lorem::word();
    }
}
