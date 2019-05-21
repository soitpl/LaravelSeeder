<?php
/**
 *
 *
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace Tests\Unit\LaravelSeeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use soIT\LaravelSeeders\Sources\File;

trait SeederTestFileMock
{
    protected $testFileName;
    protected $testData = ["test-1" => "Test1", "test-2" => "Test2"];

    protected function _createTestFile($fileName = null)
    {
        $this->testFileName = $fileName ? $fileName : Str::random(20) . '.json';

        Storage::put(File::DEFAULT_DIRECTORY . '/' . $this->testFileName, json_encode($this->testData));
    }

    protected function _pathTestFile(): string
    {
        return Storage::path(File::DEFAULT_DIRECTORY . '/' . $this->testFileName);
    }

    protected function _deleteTestFile()
    {
        Storage::delete(File::DEFAULT_DIRECTORY . '/' . $this->testFileName);
    }
}
