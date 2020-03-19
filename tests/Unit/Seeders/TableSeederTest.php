<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Types;

use Orchestra\Testbench\TestCase;
use soIT\LaravelSeeders\Containers\DataContainer;
use soIT\LaravelSeeders\Enums\Duplicated;
use soIT\LaravelSeeders\Exceptions\ColumnNotFoundException;
use soIT\LaravelSeeder\Exceptions\SeedTargetFoundException;
use soIT\LaravelSeeders\Seeders\TableSeeder;
use Tests\Unit\LaravelSeeders\DatabaseTableMockTrait;

class TableSeederTest extends TestCase
{
    use DatabaseTableMockTrait;

    public function setUp():void
    {
        parent::setUp();
        $this->createTestTable();
    }

    /**
     * @throws SeedTargetFoundException
     */
    public function testConstructorWithExistedTableName()
    {
        $tableType = new TableSeeder(self::$testTableName);
        $this->assertEquals(self::$testTableName, $tableType->getName());
    }

    /**
     * @throws \soIT\LaravelSeeder\Exceptions\SeedTargetFoundException
     */
    public function testConstructorWithNoExistedTableName()
    {
        $this->expectException(SeedTargetFoundException::class);

        new TableSeeder(self::$testTableName.'-non');
    }

    /**
     * @throws \soIT\LaravelSeeder\Exceptions\SeedTargetFoundException
     */
    public function testGetTableName()
    {
        $tableType = new TableSeeder(self::$testTableName);

        $this->assertEquals(self::$testTableName, $tableType->getName());
    }

    /**
     * @throws \soIT\LaravelSeeder\Exceptions\SeedTargetFoundException
     */
    public function testSetGetUniqueColumns(){
        $columns = ['id', 'name'];

        $tableSeeder = new TableSeeder(self::$testTableName);
        $tableSeeder->setUniqueKeys($columns);
        $this->assertEquals($columns, $tableSeeder->getUniqueColumns());
    }

    /**
     * @throws \soIT\LaravelSeeder\Exceptions\SeedTargetFoundException
     */
    public function testSetGetUniqueColumnsWithFalseColumn(){
        $columns = ['id', 'name', 'test'];

        $this->expectException(ColumnNotFoundException::class);

        $tableSeeder = new TableSeeder(self::$testTableName);
        $tableSeeder->setUniqueKeys($columns);
    }

    /**
     * @throws \soIT\LaravelSeeder\Exceptions\SeedTargetFoundException
     */
    public function testSave()
    {
        $tableSeeder = new TableSeeder(self::$testTableName);
        $tableSeeder->setData(new DataContainer(["name"=>'test-1']));
        $tableSeeder->save();

        $this->assertDatabaseHas(self::$testTableName, ['name'=>'test-1']);
    }

    /**
     * @throws SeedTargetFoundException
     */
    public function testSaveWithUpdateOnDuplicate()
    {
        $tableSeeder = new TableSeeder(self::$testTableName);
        $tableSeeder->setData(new DataContainer(["name"=>'test-1', 'uuid'=>'xxxx']));
        $tableSeeder->onDuplicate(Duplicated::UPDATE);
        $tableSeeder->setUniqueKeys(['uuid']);
        $tableSeeder->save();

        $this->assertDatabaseHas(self::$testTableName, ['name'=>'test-1']);

        $tableSeeder->setData(new DataContainer(["name"=>'test-2', 'uuid'=>'xxxx']));
        $tableSeeder->save();
        $this->assertDatabaseHas(self::$testTableName, ['name'=>'test-2']);
        $this->assertDatabaseMissing(self::$testTableName, ['name'=>'test-1']);
    }

    /**
     * @throws \soIT\LaravelSeeder\Exceptions\SeedTargetFoundException
     */
    public function testSaveWithIgnoreOnDuplicate()
    {
        $tableSeeder = new TableSeeder(self::$testTableName);
        $tableSeeder->setData(new DataContainer(["name"=>'test-1', 'uuid'=>'xxxx']));
        $tableSeeder->onDuplicate(Duplicated::IGNORE);
        $tableSeeder->setUniqueKeys(['uuid']);
        $tableSeeder->save();

        $this->assertDatabaseHas(self::$testTableName, ['name'=>'test-1']);

        $tableSeeder->setData(new DataContainer(["name"=>'test-2', 'uuid'=>'xxxx']));
        $tableSeeder->save();
        $this->assertDatabaseHas(self::$testTableName, ['name'=>'test-1']);
        $this->assertDatabaseMissing(self::$testTableName, ['name'=>'test-2']);
    }
}
