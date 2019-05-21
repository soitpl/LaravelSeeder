<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelSeeders\Seeders;

use soIT\LaravelSeeders\Containers\TranslationsContainer;

trait SeederTranslationsTrait
{
    /**
     * @var TranslationsContainer Mapping info
     */
    private $translations;

    /**
     * Translations
     *
     * @return TranslationsContainer
     */
    public function getTranslations(): TranslationsContainer
    {
        return $this->translations ?? new TranslationsContainer();
    }

    /**
     * Mapping
     *
     * @param TranslationsContainer $transformations
     *
     * @return SeederTranslationsTrait
     */
    public function setTranslations(TranslationsContainer $transformations)
    {
        $this->translations = $transformations;

        return $this;
    }
}
