<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
 */

namespace Tests\Unit\LaravelSeeders;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Schema;

trait DatabaseTableMockTrait
{
    use DatabaseMigrations;

    protected static $testTableName = 'table_test';

    public function createTestTable()
    {
        Schema::create(self::$testTableName, function ($table) {
            $table->increments('id')->nullable();
            $table->string('uuid')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

}