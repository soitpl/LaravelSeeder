<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT {$year}
 */

namespace soIT\LaravelSeeders\Executors\Features;

use soIT\LaravelSeeders\Containers\TranslationsContainer;
use soIT\LaravelSeeders\Executors\ExecutorAbstract;

trait TranslationPropertiesTrait
{
    /**
     * @var TranslationsContainer
     */
    private $translations;

    /**
     * Getter for translations container
     *
     * @return TranslationsContainer
     */
    public function getTranslations() : TranslationsContainer
    {
        return $this->translations ?? $this->translations = new TranslationsContainer();
    }

    /**
     * Setter for translations container
     *
     * @param TranslationsContainer $translations
     *
     * @return self|ExecutorAbstract
     */
    public function setTranslations(TranslationsContainer $translations) : ExecutorAbstract
    {
       $this->translations = $translations;

       return $this;
    }

    /**
     * Set property translation from name in seeder source to target name
     *
     * @param string $targetPropertyName
     * @param string $sourcePropertyName
     *
     * @return self|ExecutorAbstract
     */
    public function translateProperty(string $targetPropertyName, string $sourcePropertyName): ExecutorAbstract
    {
        $this->getTranslations()->add($targetPropertyName, $sourcePropertyName);

        return $this;
    }
}
