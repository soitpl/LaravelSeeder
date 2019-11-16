<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT {2019}
 */

namespace soIT\LaravelSeeders\Sources;

class MultiFiles implements SourceInterface
{
    /**
     * @var File[] Array of files objects
     */
    private $files = [];

    /**
     * MultiFiles constructor.
     *
     * @param array $files
     */
    public function __construct(array $files)
    {
        $this->setFiles($files);
    }

    /**
     * Create class instace from glob standard pattern
     *
     * @param static Glob path pattern
     * @return self
     * @see https://facelessuser.github.io/wcmatch/glob/
     */
    public static function fromPattern(string $pattern): self
    {
        return new self(glob($pattern) ?? []);
    }

    /**
     * Get data to seed
     *
     * @return array
     */
    public function data(): array
    {
        return array_merge(
            ...array_map(function (File $item) {
                return $item->data();
            }, $this->files)
        );
    }

    /**
     * Set files form paths array
     *
     * @param array $files
     */
    public function setFiles(array $files): void
    {
        foreach ($files as $file) {
            array_push($this->files, new File($file));
        }
    }
}
