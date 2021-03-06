<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Seeders;

use Mockery;
use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Containers\NamingStrategyContainer;

class SeederTranslationsTraitTest extends TestCase
{
    public function testGetSetTransformations()
    {
        $translations = new NamingStrategyContainer();

        $seederMock = Mockery::mock(SeederTranslationsTrait::class);
        $ret = $seederMock->setTranslations($translations);

        $this->assertEquals($seederMock, $ret);
        $this->assertEquals($translations, $seederMock->getTranslations());
    }
}
