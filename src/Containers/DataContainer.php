<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders\Containers;

use Illuminate\Support\Collection;
use soIT\LaravelSeeders\Exceptions\WrongAttributeException;

class DataContainer extends Collection
{
    /**
     * DataContainer constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);

        $this->recursive();
    }

    /**
     * Recursive make of DataContainer
     */
    private function recursive(): void
    {
        foreach ($this->items as $key => $item) {
            if (is_array($item)) {
                $this->items[$key] = new DataContainer($item);
            }
        }
    }

    /**
     * Magic method for getModelName values as object
     *
     * @param string $key Value key
     *
     * @return mixed
     * @throws \Exception
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->items)) {
            return $this->items[$key];
        }

        throw new WrongAttributeException("Property [{$key}] does not exist on this collection instance.");
    }
}
