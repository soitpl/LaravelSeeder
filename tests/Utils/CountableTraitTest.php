<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
 */

namespace soIT\LaravelSeeders\Utils;

use Tests\TestCase;

class CountableTraitTest extends TestCase
{

    public function testCount()
    {
        $mock = \Mockery::mock(CountableTrait::class);

        $this->assertEquals(0, $mock->count());
    }
}
