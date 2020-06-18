<?php
/**
 * MultiFiles.php
 *
 * @lastModification 19.03.2020, 23:13
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Sources;

use soIT\LaravelSeeder\Contracts\SourceInterface;
use soIT\LaravelSeeders\Exceptions\FileDontExistException;
use soIT\LaravelSeeder\Sources\File;

class MultiFiles implements SourceInterface
{
    /**
     * @var File[] Array of files objects
     */
    private array $files = [];

    /**
     * MultiFiles constructor.
     *
     * @param array $files
     *
     * @throws FileDontExistException
     */
    public function __construct(array $files)
    {
        $this->setFiles($files);
    }

    /**
     * Create class instance from glob standard pattern
     *
     * @param string $pattern
     *
     * @return self
     * @throws FileDontExistException
     * @see https://facelessuser.github.io/wcmatch/glob/
     */
    public static function fromPattern(string $pattern):self
    {
        return new self(glob($pattern) ?? []);
    }

    /**
     * Get data to seed
     *
     * @return array
     */
    public function data():array
    {
        return array_merge(
            ...array_map(fn(File $item) => $item->data(), $this->files)
        );
    }

    /**
     * Set files form paths array
     *
     * @param array $files
     *
     * @throws FileDontExistException
     */
    public function setFiles(array $files):void
    {
        foreach ($files as $file) {
            array_push($this->files, new File($file));
        }
    }
}
