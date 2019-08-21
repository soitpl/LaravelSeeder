<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace Tests\Unit\LaravelSeeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use soIT\LaravelSeeders\Sources\File;

trait SeederTestFileMock
{
    /**
     * @var vfsStreamDirectory;
     */
    protected $root;
    protected $testFileName;
    protected $testData = ["test-1" => "Test1", "test-2" => "Test2"];

    protected function createTestFile($fileName = null)
    {
        $this->testFileName = $fileName ?: 'testFile.json';

        $this->root = vfsStream::setup('testDir');

        $file = vfsStream::newFile($this->testFileName);
        $file->setContent(json_encode($this->testData));

        $this->root->addChild($file);
    }

    protected function pathTestFile(): string
    {
        return 'vfs://'.$this->root->path().DIRECTORY_SEPARATOR.$this->testFileName;
    }

    protected function createRealTestFile($fileName = null)
    {
        $this->testFileName = $fileName ? $fileName : Str::random(20) . '.json';

        Storage::put(File::DEFAULT_DIRECTORY . '/' . $this->testFileName, json_encode($this->testData));
    }

    protected function pathRealTestFile(): string
    {
        return Storage::path(File::DEFAULT_DIRECTORY . '/' . $this->testFileName);
    }

    protected function deleteRealTestFile()
    {
        Storage::delete(File::DEFAULT_DIRECTORY . '/' . $this->testFileName);
    }
}
