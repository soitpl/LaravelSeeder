<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
 */
declare(strict_types=1);

namespace soIT\LaravelSeeders\Seeders;

use Illuminate\Database\Eloquent\Model;
use soIT\LaravelSeeders\Containers\DataContainer;
use Tests\TestCase;
use Tests\Unit\LaravelSeeders\DatabaseTableMockTrait;

class ModelSeederTest extends TestCase
{
    use DatabaseTableMockTrait;

    public function setUp()
    {
        parent::setUp();
        $this->createTestTable();
    }

    public function testGetName()
    {
        $seeder = new ModelSeeder(ModelTest::class);
        $this->assertEquals(ModelTest::class, $seeder->getName());
    }

    /**
     * @throws \soIT\LaravelSeeders\Exceptions\NoPropertySetException
     */
    public function testSave()
    {
        $data = new DataContainer(['name'=>'Test']);
        $seeder = new ModelSeeder(ModelTest::class);
        $seeder->setData($data);
        $seeder->save();

        $this->assertDatabaseHas(self::$testTableName, [
            'name' => 'Test'
        ]);
    }
}

class ModelTest extends Model{
    protected $table = 'table_test';
}
