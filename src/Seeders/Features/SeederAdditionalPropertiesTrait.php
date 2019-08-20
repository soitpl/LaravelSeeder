<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Seeders\Features;

use soIT\LaravelSeeders\Containers\AdditionalPropertiesContainer;
use soIT\LaravelSeeders\Transformations\CallableTransformation;

trait SeederAdditionalPropertiesTrait
{
    /**
     * @var AdditionalPropertiesContainer Mapping info
     */
    protected $properties;

    private function getAdditionalPropertiesInsertData(): array
    {
        $out = [];

        foreach ($this->properties as $key => $value) {
            if ($value instanceof CallableTransformation) {
                $out[$key] = $value->setPropertyName($key)->transform($value);
            }
            else {
                $out[$key] = $value;
            }
        }

        return $out;
    }

    /**
     * Additional properties
     *
     * @return AdditionalPropertiesContainer
     */
    public function getProperties(): AdditionalPropertiesContainer
    {
        return $this->properties ?? new AdditionalPropertiesContainer();
    }

    /**
     * Mapping
     *
     * @param AdditionalPropertiesContainer $properties
     *
     * @return SeederAdditionalPropertiesTrait
     */
    public function setProperties(AdditionalPropertiesContainer $properties)
    {
        $this->properties = $properties;

        return $this;
    }
}
