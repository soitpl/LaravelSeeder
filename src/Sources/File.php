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
     * @var string Seeder data file directory
     */
    protected $directory;
    /**
     * @var string Seeder data File name
     */
    protected $fileName;

    /**
     * File constructor.
     *
     * @param string $fileName If file is locate in default seeder source directory file name should be getModelName without path
     * @param string|null $dirPath If file is another location than default directory, path should be getModelName
     *
     * @throws DirectoryDontExistException
     * @throws FileDontExistException
     */
    public function __construct(string $fileName, string $dirPath = null)
    {
        $dirPath !== null ? $this->setDir($dirPath) : $this->_setDefaultPath();

        $this->setFile($fileName);
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
     * @return string Seeders directory path
     */
    public function getDir(): string
    {
        return $this->directory;
    }

    /**
     * Return file name
     *
     * @return string|null File name
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * Get full file path
     *
     * @return string File path
     */
    public function getFilePath()
    {
        return $this->directory . DIRECTORY_SEPARATOR . $this->fileName;
    }

    /**
     * Setting directory with seeder data sources
     *
     * @param string $path Path to directory
     *
     * @return File Self instance
     * @throws DirectoryDontExistException
     */
    public function setDir(string $path): self
    {
        if (!file_exists($path) || !is_dir($path)) {
            throw new DirectoryDontExistException(sprintf("Path %s don't exist or is not valid directory", $path));
        }

        $this->directory = $path;

        return $this;
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
        $path = $this->directory . DIRECTORY_SEPARATOR . $fileName;

        if (!file_exists($path) || !is_readable($path)) {
            throw new FileDontExistException(sprintf("File %s was't find in %s or is not readable", $fileName, $path));
        }

        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Set default seeder sources directory
     *
     * @return File
     * @throws DirectoryDontExistException
     */
    private function _setDefaultPath()
    {
        if (!Storage::exists(self::DEFAULT_DIRECTORY)) {
            Storage::makeDirectory(self::DEFAULT_DIRECTORY);
        }

        return $this->setDir(Storage::path(self::DEFAULT_DIRECTORY));
    }
}
