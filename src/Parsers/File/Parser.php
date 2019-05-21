<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders\Parsers\File;

use soIT\LaravelSeeders\Exceptions\FileDontExistException;
use soIT\LaravelSeeders\Exceptions\ParserNotFoundException;
use soIT\LaravelSeeders\Parsers\ParserInterface;

class Parser
{
    /**
     * Parsers array
     */
    const PARSERS = [
        'json' => JsonParser::class
    ];

    /**
     * @param string $path
     *
     * @return array
     * @throws FileDontExistException
     * @throws ParserNotFoundException
     */
    public static function parse(string $path): array
    {
        if (file_exists($path)) {
            return self::factory($path)->parse();
        } else {
            throw new FileDontExistException(sprintf('File %s not found', $path));
        }
    }

    /**
     * Create proper parser object
     *
     * @param string $path
     *
     * @return mixed
     * @throws ParserNotFoundException
     */
    private static function factory(string $path): ParserInterface
    {
        $class = self::PARSERS[self::getExtension($path)] ?? null;

        if ($class === null) {
            throw new ParserNotFoundException(sprintf('Parser for file %s was not found', $path));
        } else {
            return new $class($path);
        }
    }

    /**
     * Get path extension
     *
     * @param string $path Path to check extension
     *
     * @return string
     */
    protected static function getExtension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }
}
