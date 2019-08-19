<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders\Sources;

use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase;
use soIT\LaravelSeeders\Exceptions\DirectoryDontExistException;
use soIT\LaravelSeeders\Exceptions\FileDontExistException;
use Tests\Unit\LaravelSeeders\SeederTestFileMock;

class FileStub
{
    const DEFAULT_DIRECTORY = 'test_directory';
}

class FileTest extends TestCase
{
    use SeederTestFileMock;

    public function setUp():void
    {
        parent::setUp();
        $this->createRealTestFile();
    }

    public function tearDown():void
    {
        $this->deleteRealTestFile();
        parent::tearDown();
    }

    /**
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     */
    public function testConstructorWithDefaultDirectory()
    {
        $fileObj = new File($this->testFileName);

        $this->assertTrue(file_exists($fileObj->getFilePath()));
        $this->assertEquals($this->testFileName, basename($fileObj->getFilePath()));
    }

    /**
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     */
    public function testConstructorWithNonDefaultDirectory()
    {
        $this->createTestFile();
        $fileObj = new File($this->pathTestFile());

        $this->assertTrue(file_exists($this->pathTestFile()));
        $this->assertEquals($this->testFileName, basename($fileObj->getFilePath()));;
    }

    /**
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     * @throws \soIT\LaravelSeeders\Exceptions\ParserNotFoundException
     */
    public function testData()
    {
        $instance = new File($this->testFileName);
        $this->assertEquals($this->testData, $instance->data());
    }

    /**
     * @throws FileDontExistException
     */
    public function testGetPath()
    {
        $instance = new File($this->testFileName);

        $this->assertEquals(
            Storage::path(File::DEFAULT_DIRECTORY . DIRECTORY_SEPARATOR . $this->testFileName),
            $instance->getFilePath()
        );
    }

    /**
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     */
    public function testSetFile()
    {
        $this->expectException(FileDontExistException::class);
        $instance = new File('xxx.json');

        $returnInstance = $instance->setFile($this->testFileName);

        $this->assertInstanceOf(File::class, $returnInstance);
        $this->assertEquals($this->testFileName, $instance->getFileName());
    }

    /**
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     */
    public function testSetFileWhichDontExists()
    {
        $this->expectException(FileDontExistException::class);
        $instance = new File('xxx.json');

        $this->expectException(FileDontExistException::class);
        $instance->setFile($this->testFileName . 'x');
    }
}
