<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders\Sources;

use Illuminate\Support\Facades\Storage;
use soIT\LaravelSeeders\Exceptions\DirectoryDontExistException;
use soIT\LaravelSeeders\Exceptions\FileDontExistException;
use Tests\TestCase;
use Tests\Unit\LaravelSeeders\SeederTestFileMock;

class FileStub
{
    const DEFAULT_DIRECTORY = 'test_directory';
}

class FileTest extends TestCase
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
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     */
    public function testConstructorWithDefaultDirectory()
    {
        $fileObj = new File($this->testFileName);

        $this->assertEquals($this->testFileName, $fileObj->getFileName());
    }

    /**
     * @runInSeparateProcesses
     * @preserveGlobalState disable
     */
    //    public function testConstructorWithNonExistDefaultDirectory()
    //    {
    //    }

    /**
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     */
    public function testConstructorWithNonExistDirectory()
    {
        $this->expectException(DirectoryDontExistException::class);
        $fileObj = new File($this->testFileName, '/test');
    }

    /**
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     */
    public function testConstructorWithNonDefaultDirectory()
    {
        $file = __FILE__;
        $fileObj = new File(basename($file), dirname($file));

        $this->assertEquals(basename($file), $fileObj->getFileName());
        $this->assertEquals(dirname($file), $fileObj->getDir());
        $this->assertEquals($file, $fileObj->getFilePath());
    }

    /**
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     * @throws \soIT\LaravelSeeders\Exceptions\ParserNotFoundException
     */
    public function testData()
    {
        $instance = $this->_makeTestClassInstance();
        $this->assertEquals($this->testData, $instance->data());
    }

    /**
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     */
    public function testGetDir()
    {
        $fileObj = $this->_makeTestClassInstance();

        $defaultPath = $fileObj->getDir();

        $this->assertEquals(Storage::path(File::DEFAULT_DIRECTORY), $defaultPath);
    }

    public function testGetPath()
    {
        $instance = $this->_makeTestClassInstance();
        $this->assertEquals(
            Storage::path(File::DEFAULT_DIRECTORY . DIRECTORY_SEPARATOR . $this->testFileName),
            $instance->getFilePath()
        );
    }

    /**
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     */
    public function testSetDir()
    {
        $testPath = database_path();

        $instance = $this->_makeTestClassInstance();
        $returnInstance = $instance->setDir($testPath);

        $this->assertInstanceOf(File::class, $returnInstance);
        $this->assertEquals($testPath, $instance->getDir());
    }

    /**
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     */
    public function testSetFile()
    {
        $instance = $this->_makeTestClassInstance();

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
        $instance = $this->_makeTestClassInstance();

        $this->expectException(FileDontExistException::class);
        $instance->setFile($this->testFileName . 'x');
    }

    /**
     * @return File
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     */
    protected function _makeTestClassInstance(): File
    {
        if (!$this->testFileName) {
            $this->_createTestFile();
        }
        return new File($this->testFileName);
    }
}
