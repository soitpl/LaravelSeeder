<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders\Sources;

use soIT\LaravelSeeders\Exceptions\WrongAttributeException;

class Entity implements SourceInterface
{
    /**
     * @var array Data to seed
     */
    protected $data;

    /**
     * Direct constructor.
     *
     * @param array $data
     *
     * @throws WrongAttributeException
     */
    public function __construct(array $data)
    {
        $this->_setEntity($data);
    }

    /**
     * Return collection with data to seed
     *
     * @return array
     */
    public function data(): array
    {
        return [$this->data];
    }

    /**
     * Set entity data
     *
     * @param array $data
     *
     * @throws WrongAttributeException
     */
    private function _setEntity(array $data): void
    {
        if ($this->isAssociativeArray($data)) {
            $this->data = $data;
        } else {
            throw new WrongAttributeException(
                sprintf('Wrong attribute for %s - only associative array is allowed', get_class($this))
            );
        }
    }

    /**
     * Check array has only string keys
     *
     * @param array $array
     *
     * @return bool
     */
    private function isAssociativeArray(array $array): bool
    {
        return count(array_filter(array_keys($array), 'is_string')) == count($array);
    }
}
