<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */
namespace soIT\LaravelSeeders\Containers;

use Illuminate\Support\Collection;

class DataContainer extends Collection
{
    public function __construct($items = [])
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

        return parent::__get($key);
    }
}
