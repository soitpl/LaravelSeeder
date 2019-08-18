<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
 */

namespace soIT\LaravelSeeders\Seeders;

use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Containers\TransformationsContainer;

class SeederTransformationsTraitTest extends TestCase
{
    public function testGetSetTransformations()
    {
        $transformations = new TransformationsContainer();

        $seederMock = \Mockery::mock(SeederTransformationsTrait::class);
        $ret = $seederMock->setTransformations($transformations);

        $this->assertEquals($seederMock, $ret);
        $this->assertEquals(
            $transformations,
            $seederMock->getTransformations()
        );
    }
}
