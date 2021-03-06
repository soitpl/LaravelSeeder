<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Executors;

use Mockery;
use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeder\Executors\ModelExecutor;
use soIT\LaravelSeeders\Containers\DataContainer;
use soIT\LaravelSeeders\Containers\TransformationsContainer;
use soIT\LaravelSeeders\Seeders\SeederAbstract;

class ModelExecutorTest extends TestCase
{
    private const MODEL_NAME = Model::class;

    public function testConstructor()
    {
        $executor = new ModelExecutor(self::MODEL_NAME);
        $seeder = $executor->getSeeder();

        $this->assertInstanceOf(SeederAbstract::class, $seeder);
        $this->assertEquals(self::MODEL_NAME, $seeder->getName());

        $this->assertEquals(new TransformationsContainer(), $executor->getTransformations());
    }

    public function testConstructorWithTransformations()
    {
        $transformationsContainer = new TransformationsContainer();

        $executor = new ModelExecutor(self::MODEL_NAME, $transformationsContainer);
        $seeder = $executor->getSeeder();

        $this->assertInstanceOf(SeederAbstract::class, $seeder);
        $this->assertEquals(self::MODEL_NAME, $seeder->getName());

        $this->assertEquals($transformationsContainer, $executor->getTransformations());
    }

    public function testAssignModel()
    {
        $executor = new ModelExecutor(self::MODEL_NAME);
        $retExecutor = $executor->assignModel('prop_1', 'Model_1');

        $this->assertInstanceOf(ModelExecutor::class, $retExecutor);
    }

    public function testExecute()
    {
        $data = new DataContainer([new DataContainer(['test' => '1']), new DataContainer(['test2' => '2'])]);
        $executor = new ModelExecutor(self::MODEL_NAME);

        $seederMock = Mockery::mock(SeederAbstract::class);
        $seederMock
            ->shouldReceive('setData')
            ->withArgs([DataContainer::class])
            ->andReturn($seederMock);
        $seederMock->shouldReceive('save')->andReturn(true, true, false);
        $seederMock->shouldReceive('setTransformations')->andReturn(null);
        $seederMock->shouldReceive('setTranslations')->andReturn(null);

        $executor->setSeeder($seederMock);

        $ret = $executor->execute($data);

        $this->assertTrue($ret);
    }
}

class Model extends \Illuminate\Database\Eloquent\Model
{
}
