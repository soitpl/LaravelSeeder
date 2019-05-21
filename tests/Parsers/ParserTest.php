<?php
/**
 *
 *
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders\Parsers\File;

use Illuminate\Support\Str;
use soIT\LaravelSeeders\Exceptions\FileDontExistException;
use soIT\LaravelSeeders\Exceptions\ParserNotFoundException;
use Tests\TestCase;
use Tests\Unit\LaravelSeeders\SeederTestFileMock;

class ParserTest extends TestCase
{
    use SeederTestFileMock;

    public function setUp()
    {
        parent::setUp();

        $this->_createTestFile();
    }

    public function tearDown()
    {
        $this->_deleteTestFile();

        parent::tearDown();
    }

    /**
     * @throws \soIT\LaravelSeeders\Exceptions\FileDontExistException
     * @throws \soIT\LaravelSeeders\Exceptions\ParserNotFoundException
     */
    public function testParse()
    {
        $data = Parser::parse($this->_pathTestFile());

        $this->assertIsArray($data);
        $this->assertEquals($this->testData, $data);
    }

    /**
     * @throws \soIT\LaravelSeeders\Exceptions\FileDontExistException
     * @throws \soIT\LaravelSeeders\Exceptions\ParserNotFoundException
     */
    public function testParseFileDontExist()
    {
        $this->expectException(FileDontExistException::class);
        Parser::parse($this->_pathTestFile() . 'x');
    }

    /**
     * @throws \soIT\LaravelSeeders\Exceptions\FileDontExistException
     * @throws \soIT\LaravelSeeders\Exceptions\ParserNotFoundException
     */
    public function testParseParserDontExist()
    {
        $this->_deleteTestFile();
        $this->_createTestFile(Str::random(20) . '.jsonx');

        $this->expectException(ParserNotFoundException::class);
        Parser::parse($this->_pathTestFile());
    }
}
