<?php
/**
 *
 *
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders\Parsers\File;

use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeders\Exceptions\FileDontExistException;
use soIT\LaravelSeeders\Exceptions\ParserNotFoundException;
use Tests\Unit\LaravelSeeders\SeederTestFileMock;


class ParserTest extends TestCase
{
    use SeederTestFileMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->createTestFile();
    }

    /**
     * @throws \soIT\LaravelSeeders\Exceptions\FileDontExistException
     * @throws \soIT\LaravelSeeders\Exceptions\ParserNotFoundException
     */
    public function testParse()
    {
        $data = Parser::parse($this->pathTestFile());

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
        Parser::parse($this->pathTestFile().'x');
    }

    /**
     * @throws \soIT\LaravelSeeders\Exceptions\FileDontExistException
     * @throws \soIT\LaravelSeeders\Exceptions\ParserNotFoundException
     */
    public function testParseParserDontExist()
    {
        $this->createTestFile(Str::random(20).'.jsonx');

        $this->expectException(ParserNotFoundException::class);
        Parser::parse($this->pathTestFile());
    }
}