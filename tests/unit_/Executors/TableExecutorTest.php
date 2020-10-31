<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Executors;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use Mockery;
use Orchestra\Testbench\TestCase;
use soIT\LaravelSeeder\Exceptions\SeedTargetFoundException;
use soIT\LaravelSeeder\Executors\TableExecutor;
use soIT\LaravelSeeders\Containers\DataContainer;
use soIT\LaravelSeeders\Enums\Duplicated;
use soIT\LaravelSeeders\Seeders\SeederAbstract;
use soIT\LaravelSeeders\Seeders\TableSeeder;

class TableExecutorTest extends TestCase
{
    use DatabaseMigrations;

    private const TABLE_NAME = 'TableTest';
    public const TEST_DATA = ['x' => 'y', 'a' => ['a' => 'b']];

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws SeedTargetFoundException
     */
    public function testConstructor()
    {
        $factoryMock = Mockery::mock(
            'overload:' . TableSeeder::class,
            SeederAbstract::class
        )->shouldAllowMockingProtectedMethods();

        $factoryMock->shouldReceive('_isTableExists')->andReturn(true);
        $factoryMock->shouldReceive('getName')->andReturn(self::TABLE_NAME);

        $instance = new TableExecutor(self::TABLE_NAME);
        $this->assertEquals(self::TABLE_NAME, $instance->getSeeder()->getName());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws SeedTargetFoundException
     */
    public function testGetTarget()
    {
        $factoryMock = Mockery::mock('overload:' . TableSeeder::class, SeederAbstract::class);
        $factoryMock->shouldReceive('getName')->andReturn(self::TABLE_NAME);

        $instance = new TableExecutor('users');
        $this->assertInstanceOf(TableSeeder::class, $instance->getSeeder());
    }

    /**
     * @throws SeedTargetFoundException
     */
    public function testExecute()
    {
        $data = new DataContainer([new DataContainer(['test' => '1']), new DataContainer(['test2' => '2'])]);
        $executor = new TableExecutor('users');

        $seederMock = Mockery::mock(TableSeeder::class);
        $seederMock
            ->shouldReceive('setData')
            ->withArgs([DataContainer::class])
            ->andReturn($seederMock);

        $seederMock->shouldReceive('save')->andReturn(true, true, false);
        $seederMock->shouldReceive('setTransformations')->andReturn(null);
        $seederMock->shouldReceive('setTranslations')->andReturn(null);
        $seederMock->shouldReceive('setTable')->andReturn(true);

        $executor->setSeeder($seederMock);

        $ret = $executor->execute($data);

        $this->assertTrue($ret);

        $ret = $executor->execute($data);
        $this->assertFalse($ret);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws SeedTargetFoundException
     */
    public function testOnDuplicate(){
        $factoryMock = Mockery::mock(
            'overload:' . TableSeeder::class,
            SeederAbstract::class
        )->shouldAllowMockingProtectedMethods();

        $factoryMock->shouldReceive('_isTableExists')->andReturn(true);
        $factoryMock->shouldReceive('onDuplicate')->once()->andReturn($factoryMock);
        $factoryMock->shouldReceive('setUniqueKeys')->andReturn(true);

        $executor = new TableExecutor(self::TABLE_NAME);

        $ret = $executor->onDuplicate(Duplicated::IGNORE, ['id']);

       $this->assertEquals($executor, $ret);
    }
}
