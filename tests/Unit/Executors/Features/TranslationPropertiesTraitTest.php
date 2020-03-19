<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Executors\Features;


use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Containers\TranslationsContainer;
use soIT\LaravelSeeder\Executors\ExecutorAbstract;

class TranslationPropertiesTraitTest extends TestCase
{

    public function testSetTranslations()
    {
        /**
         * @var HasPropertiesTranslation $mock
         */
        $mock = $this->_createTraitMock();
        $translations = new TranslationsContainer();
        $translations['x'] = 'y';

        $ret = $mock->setTranslations($translations);
        $this->assertEquals($mock, $ret);
        $this->assertEquals($translations, $mock->getTranslations());
    }

    public function testGetTranslations()
    {
        /**
         * @var HasPropertiesTranslation $mock
         */
        $mock = $this->_createTraitMock();

        $this->assertEquals(new TranslationsContainer(), $mock->getTranslations());

        $translations = new TranslationsContainer();
        $translations['x'] = 'y';

        $ret = $mock->setTranslations($translations);
        $this->assertEquals($mock, $ret);
        $this->assertEquals($translations, $mock->getTranslations());
    }

    public function testTranslateProperty()
    {
        /**
         * @var HasPropertiesTranslation $mock
         */
        $mock = $this->_createTraitMock();

        $this->assertEquals(new TranslationsContainer(), $mock->getTranslations());

        $translations = new TranslationsContainer();
        $translations['x'] = 'y';

        $ret = $mock->translateProperty('y', 'x');
        $this->assertEquals($translations, $ret->getTranslations());
    }

    private function _createTraitMock()
    {
        return \Mockery::mock(HasPropertiesTranslation::class, ExecutorAbstract::class);
    }
}
