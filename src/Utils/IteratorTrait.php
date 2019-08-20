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
     * @var array Items array
     */
    private $items = [];

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
    function set(array $items)
    {
        $this->items = $items;
    }

    /**
     * @inheritdoc
     */
    function rewind()
    {
        return reset($this->items);
    }

    /**
     * @inheritdoc
     */
    function current()
    {
        return current($this->items);
    }

    /**
     * @inheritdoc
     */
    function key()
    {
        return key($this->items);
    }

    /**
     * @inheritdoc
     */
    function next()
    {
        return next($this->items);
    }

    /**
     * @inheritdoc
     */
    function valid()
    {
        return key($this->items) !== null;
    }
}
