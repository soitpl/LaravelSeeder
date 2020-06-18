<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Seeders\Traits;

use soIT\LaravelSeeder\Containers\NamingStrategyContainer;

trait SeederTranslationsTrait
{
    /**
     * @var NamingStrategyContainer Mapping info
     */
    private NamingStrategyContainer $translations;

    /**
     * Translations
     *
     * @return NamingStrategyContainer
     */
    public function getTranslations(): NamingStrategyContainer
    {
        return $this->translations ?? new NamingStrategyContainer();
    }

    /**
     * Mapping
     *
     * @param NamingStrategyContainer $transformations
     *
     * @return SeederTranslationsTrait
     */
    public function setTranslations(NamingStrategyContainer $transformations)
    {
        $this->translations = $transformations;

        return $this;
    }
}
