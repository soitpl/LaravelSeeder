<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders\Parsers\File;

use soIT\LaravelSeeders\Parsers\ParserInterface;

class JsonParser implements ParserInterface
{
    /**
     * @var string Path to file
     */
    protected $path;

    /**
     * JsonParser constructor.
     *
     * @param string $path File path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Pare json file to array of objects
     *
     * @return array
     */
    public function parse(): array
    {
        return json_decode(file_get_contents($this->path), true);
    }
}
