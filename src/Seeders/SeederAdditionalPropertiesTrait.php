<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT {$year}
 */

namespace soIT\LaravelSeeders\Seeders;

use soIT\LaravelSeeders\Containers\AdditionalPropertiesContainer;

trait SeederAdditionalPropertiesTrait
{
    /**
     * @var AdditionalPropertiesContainer Mapping info
     */
    protected $properties;

    private function getAdditionalPropertiesInsertData(): array
    {
        print_r($this->properties);
        $out = [];

        foreach ($this->properties as $key => $value) {
            $out[$key] = $this->data[$key];
        }
        print_r($out);
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
