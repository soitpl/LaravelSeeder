<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */
namespace soIT\LaravelSeeders\Sources;

use Illuminate\Support\Facades\Storage;
use soIT\LaravelSeeders\Exceptions\DirectoryDontExistException;
use soIT\LaravelSeeders\Exceptions\FileDontExistException;
use soIT\LaravelSeeders\Parsers\File\Parser;

class File implements SourceInterface
{
    const DEFAULT_DIRECTORY = 'seeders';
    /**
     * @var string Seeder data File name
     */
    protected $filePath;

    /**
     * File constructor.
     *
     * @param string $fileName If file is locate in default seeder source directory file name should be getModelName without path
     *
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     */
    public function __construct(string $file)
    {
        $this->setFile($file);
    }

    /**
     * @inheritdoc
     *
     * @return array
     * @throws FileDontExistException
     * @throws \soIT\LaravelSeeders\Exceptions\ParserNotFoundException
     */
    public function data()
    {
        return Parser::parse($this->getFilePath());
    }

    /**
     * Return file name
     *
     * @return string|null File name
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * Set seeder data source file
     *
     * @param string $fileName File name without without path.
     *
     * @return File This object
     * @throws FileDontExistException
     */
    public function setFile(string $fileName): self
    {
        if (!$this->setFileWithPath($fileName) && !$this->setFileInDefaultLocation($fileName) && !$this->setFileByRootPath($fileName)) {
            throw new FileDontExistException(sprintf("File %s was't find or is not readable", $fileName));
        }

        return $this;
    }

    /**
     * Check is file exists
     *
     * @param $fileName
     *
     * @return bool
     */
    private function isFileExists(string $path): bool {
        print_r($path);
        echo ' | ';
        return file_exists($path) && is_readable($path);
    }

    private function setFileByRootPath(string $path): bool {
        return $this->setFileWithPath(base_path().$path);
    }

    /**
     * Try set file stored in default location
     *
     * @param string $fileName
     *
     * @return bool
     * @throws DirectoryDontExistException
     */
    private function setFileInDefaultLocation(string $path): bool {
        return $this->setFileWithPath($this->getDefaultPath().$path);
    }

    /**
     * Try set file when full path is set
     *
     * @return bool
     */
    private function setFileWithPath(string $file): bool {
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
     * @throws DirectoryDontExistException
     */
    private function getDefaultPath(): string
    {
        if (!Storage::exists(self::DEFAULT_DIRECTORY)) {
            Storage::makeDirectory(self::DEFAULT_DIRECTORY);
        }

        return Storage::path(self::DEFAULT_DIRECTORY).DIRECTORY_SEPARATOR;
    }
}
