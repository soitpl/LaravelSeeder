<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Executors\Traits;

use soIT\LaravelSeeders\Containers\TranslationsContainer;
use soIT\LaravelSeeders\Executors\ExecutorAbstract;

trait HasPropertiesTranslation
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