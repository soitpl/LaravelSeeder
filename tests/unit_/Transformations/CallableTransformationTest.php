<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Tests\Unit\Transformations;

use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeder\Containers\AdditionalPropertiesContainer;
use soIT\LaravelSeeder\Transformations\CallableTransformation;

class CallableTransformationTest extends TestCase
{

    public function testTransform()
    {
        $transformation = new CallableTransformation(function($var){
            return $var.'-tested';
        });
        $this->assertEquals('variable-tested', $transformation->transform('variable', new AdditionalPropertiesContainer()));
    }

    public function testSetPropertyName()
    {
        $transformation = new CallableTransformation(function(){});
        $ret = $transformation->setPropertyName('test');
        $this->assertEquals($transformation, $ret);
    }
}
