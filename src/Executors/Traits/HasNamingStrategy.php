<?php
/**
 * LaravelSeeder Library
 *
 * @file HasNamingStrategy.php
 * @lastModification 15.05.2020, 07:56
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Executors\Traits;

use soIT\LaravelSeeder\Containers\NamingStrategyContainer;
use soIT\LaravelSeeder\Contracts\ExecutorInterface;

trait HasNamingStrategy
{
    /**
     * @var NamingStrategyContainer
     */
    private NamingStrategyContainer $translations;

    /**
     * Getter for translations container
     *
     * @return NamingStrategyContainer
     */
    public function getNamingStrategy():NamingStrategyContainer
    {
        return $this->translations ?? $this->translations = $this->createNamingStrategyContainer();
    }

    /**
     * Setter for translations container
     *
     * @param NamingStrategyContainer $translations
     *
     * @return self|ExecutorInterface
     * @codeCoverageIgnore
     */
    public function setNamingStrategy(NamingStrategyContainer $translations):ExecutorInterface
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
     * @return self|ExecutorInterface
     * @codeCoverageIgnore
     */
    public function translateProperty(string $targetPropertyName, string $sourcePropertyName):ExecutorInterface
    {
        $this->getNamingStrategy()->add($targetPropertyName, $sourcePropertyName);

        return $this;
    }

    /**
     * @return NamingStrategyContainer
     * @codeCoverageIgnore
     */
    protected function createNamingStrategyContainer():NamingStrategyContainer
    {
        return new NamingStrategyContainer();
    }
}
