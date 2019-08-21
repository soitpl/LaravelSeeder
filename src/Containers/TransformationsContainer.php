<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Containers;

use ArrayAccess;
use Countable;
use Iterator;
use soIT\LaravelSeeders\Transformations\CallableTransformation;
use soIT\LaravelSeeders\Transformations\TransformationsInterface;
use soIT\LaravelSeeders\Utils\ArrayAccessTrait;
use soIT\LaravelSeeders\Utils\Converters;
use soIT\LaravelSeeders\Utils\IteratorTrait;

class TransformationsContainer implements Iterator, ArrayAccess, Countable
{
    use IteratorTrait, ArrayAccessTrait;

    /**
     * Assign element to property name
     *
     * @param string $property Property name
     * @param TransformationsInterface $value Element to assign, could be model, function, static
     */
    public function assign(string $property, TransformationsInterface $value): void
    {
        $array = Converters::stringToArray($property);

        $temp = &$this->items;

        foreach ($array as $key) {
            $temp[$key] = [];

            $temp = &$temp[$key];
        }

        $temp[] = $value;
    }

    /**
     * Assign callback function to property
     *
     * @param string $property
     * @param callable $callback
     */
    public function assignCallback(string $property, callable $callback): void
    {
        $this->assign($property, new CallableTransformation($callback));
    }

    /**
     * Get assigned transformation by property name
     *
     * @param string $property
     *
     * @return TransformationsInterface
     */
    public function getTransformation(string $property): ?TransformationsInterface
    {
        if ($transformation = $this->getByPropertyName($property)) {
            return $transformation[0] ?? null;
        }

        return null;
    }

    /**
     * Get property seed value for getModelName property value
     *
     * @param string $property
     * @param mixed $value
     *
     * @return mixed
     */
    public function getValue(string $property, $value)
    {
        $items = $this->getByPropertyName($property);

        if (is_null($items) || !isset($items[0])) {
            return $value;
        }

        /**
         * @var TransformationsInterface $transform
         */
        $transform = $items[0];
        unset($items[0]);

        return $transform
            ->setPropertyName($property)
            ->setTransformationsContainer((new TransformationsContainer)->assignArray($items))
            ->transform($value);
    }

    /**
     * Count number of set elements
     *
     * @return int Number od mappings
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Assign array of defined transformations
     *
     * @param array|null $array
     *
     * @return TransformationsContainer
     */
    private function assignArray(?array $array): self
    {
        $this->items = $array;

        return $this;
    }

    /**
     * Get all elements by defined path
     *
     * @param string $property Property name
     *
     * @return array
     */
    private function getByPropertyName(string $property): ?array
    {
        return $this->items[$property] ?? null;
    }
}
