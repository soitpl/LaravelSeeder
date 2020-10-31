<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders\Utils;

/**
 * Trait for implement iterator in class
 */
trait IteratorTrait
{
    /**
     * Get all items
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Set items
     *
     * @param array $items Items to set
     */
    public function set(array $items)
    {
        $this->items = $items;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        return reset($this->items);
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return key($this->items) !== null;
    }
}
