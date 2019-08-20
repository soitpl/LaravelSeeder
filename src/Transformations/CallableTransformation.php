<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */
namespace soIT\LaravelSeeders\Transformations;

class CallableTransformation implements TransformationsInterface
{
    /**
     * @var callback Callback name
     */
    private $callback;
    /**
     * @var string Property name for transformation
     */
    private $propertyName;

    /**
     * CallableTransformation constructor.
     *
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Transform param value
     *
     * @param string $propertyValue
     *
     * @return mixed
     */
    public function transform($propertyValue)
    {
        return call_user_func($this->callback, $propertyValue);
    }

    /**
     * Set property name
     *
     * @param string $property
     *
     * @return TransformationsInterface
     */
    public function setPropertyName(string $property): TransformationsInterface
    {
        $this->propertyName = $property;

        return $this;
    }
}
