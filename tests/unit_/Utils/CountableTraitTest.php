<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Utils;

use Mockery;
use PHPUnit\Framework\TestCase;

class CountableTraitTest extends TestCase
{
    public function testCount()
    {
        $mock = Mockery::mock(CountableTrait::class);

        $this->assertEquals(0, $mock->count());
    }
}
