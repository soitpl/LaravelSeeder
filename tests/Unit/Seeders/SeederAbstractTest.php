<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
 */

namespace soIT\LaravelSeeders\Seeders;


use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Containers\DataContainer;
use soIT\LaravelSeeders\Enums\Duplicated;

class SeederAbstractTest extends TestCase
{

    public function testOnDuplicate()
    {
        $seederMock = $this->getMockForAbstractClass(SeederAbstract::class);
        $ret = $seederMock->onDuplicate(Duplicated::UPDATE);
        $this->assertEquals($seederMock, $ret);
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetSetData()
    {
        $data = new DataContainer(['x'=>'y', 'a'=>'b']);

        $seederMock = $this->getMockForAbstractClass(SeederAbstract::class);
        $seederMock->setData($data);
        $this->assertEquals($data, $seederMock->getData());
    }
}
