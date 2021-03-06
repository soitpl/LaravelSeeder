<?php
/**
 * File.php
 *
 * @lastModification 19.03.2020, 23:16
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Sources;

use Illuminate\Support\Facades\Storage;
use soIT\LaravelSeeder\Contracts\SourceInterface;
use soIT\LaravelSeeders\Exceptions\FileDontExistException as FileDontExistExceptionAlias;
use soIT\LaravelSeeders\Exceptions\ParserNotFoundException;
use soIT\LaravelSeeders\Parsers\File\Parser;

class File implements SourceInterface
{
    private const DEFAULT_DIRECTORY = 'seeders';
    /**
     * @var string Seeder data File name
     */
    protected string $filePath;

    /**
     * File constructor.
     *
     * @param string $file
     *
     * @throws FileDontExistExceptionAlias
     */
    public function __construct(string $file)
    {
        $this->setFile($file);
    }

    /**
     * @inheritdoc
     *
     * @return array
     * @throws FileDontExistExceptionAlias
     * @throws ParserNotFoundException
     */
    public function data():array
    {
        return Parser::parse($this->getFilePath());
    }

    /**
     * Return file name
     *
     * @return string|null File name
     */
    public function getFilePath():?string
    {
        return $this->filePath;
    }

    /**
     * Set seeder data source file
     *
     * @param string $fileName File name without without path.
     *
     * @return File This object
     * @throws FileDontExistExceptionAlias
     */
    public function setFile(string $fileName):self
    {
        if (
            !$this->setFileWithPath($fileName) &&
            !$this->setFileInDefaultLocation($fileName) &&
            !$this->setFileByRootPath($fileName)
        ) {
            throw new FileDontExistExceptionAlias(sprintf("File %s was't find or is not readable", $fileName));
        }

        return $this;
    }

    /**
     * Check is file exists
     *
     * @param string $path
     *
     * @return bool
     */
    private function isFileExists(string $path):bool
    {
        return file_exists($path) && is_readable($path);
    }

    /**
     * Set file path according to root directory
     *
     * @param string $path
     *
     * @return bool
     */
    private function setFileByRootPath(string $path):bool
    {
        return $this->setFileWithPath(base_path().$path);
    }

    /**
     * Try set file stored in default location
     *
     * @param string $path
     *
     * @return bool
     */
    private function setFileInDefaultLocation(string $path):bool
    {
        return $this->setFileWithPath($this->getDefaultPath().$path);
    }

    /**
     * Try set file when full path is set
     *
     * @param string $file
     *
     * @return bool
     */
    private function setFileWithPath(string $file):bool
    {
        if ($this->isFileExists($file)) {
            $this->filePath = $file;
            return true;
        }

        return false;
    }

    /**
     * Set default seeder sources directory
     *
     * @return File
     */
    private function getDefaultPath():string
    {
        if (!Storage::exists(self::DEFAULT_DIRECTORY)) {
            Storage::makeDirectory(self::DEFAULT_DIRECTORY);
        }

        return Storage::path(self::DEFAULT_DIRECTORY).DIRECTORY_SEPARATOR;
    }
}
