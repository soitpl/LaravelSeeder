<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
 */

namespace soIT\LaravelSeeders\Executors\Features;

use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Containers\TransformationContainer;
use soIT\LaravelSeeders\Executors\ExecutorAbstract;
use soIT\LaravelSeeders\Containers\AdditionalProperiesConatiner;

class TransformationPropertiesTraitTest extends TestCase
{
    public function testSetTransformations()
    {
        /**
         * @var TransformationPropertiesTrait $mock
         */
        $mock = $this->_createTraitMock();
        $transformations = new AdditionalProperiesConatiner();

        $ret = $mock->setTransformations($transformations);
        $this->assertEquals($mock, $ret);
        $this->assertEquals($transformations, $mock->getTransformations());
    }

    public function testGetTranslations()
    {
        /**
         * @var TransformationPropertiesTrait $mock
         */
        $mock = $this->_createTraitMock();

        $this->assertEquals(new AdditionalProperiesConatiner(), $mock->getTransformations());

        $transformations = new AdditionalProperiesConatiner();
        $transformations['x'] = 'y';

        $ret = $mock->setTransformations($transformations);
        $this->assertEquals($mock, $ret);
        $this->assertEquals($transformations, $mock->getTransformations());
    }

    public function testAssignCallback()
    {
        $property = Str::random(15);
        $value = function () {
            return 'ok';
        };

        /**
         * @var TransformationPropertiesTrait $mock
         */
        $mock = $this->_createTraitMock();

        $transformationContainerMock = \Mockery::mock(AdditionalProperiesConatiner::class);
        $transformationContainerMock
            ->shouldReceive('assignCallback')
            ->withArgs([$property, $value])
            ->andReturn(null);

        $mock->setTransformations($transformationContainerMock);
        $ret = $mock->assignCallback($property, $value);

        $this->assertEquals($mock, $ret);
    }

    private function _createTraitMock()
    {
        return \Mockery::mock(TransformationPropertiesTrait::class, ExecutorAbstract::class);
    }
}
