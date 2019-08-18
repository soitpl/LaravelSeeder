<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
 */

namespace soIT\LaravelSeeders\Transformations;

use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Containers\TransformationsContainer;

class CallableTransformationTest extends TestCase
{

    public function testTransform()
    {
        $transformation = new CallableTransformation(function($var){
            return $var.'-tested';
        });
        $this->assertEquals('variable-tested', $transformation->transform('variable', new TransformationsContainer()));
    }

    public function testSetPropertyName()
    {
        $transformation = new CallableTransformation(function(){});
        $ret = $transformation->setPropertyName('test');
        $this->assertEquals($transformation, $ret);
    }
}
