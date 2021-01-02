<?php
/**
 * ExecutorInterface.php
 *
 * @lastModification 16.11.2019, 18:20
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeder\Contracts;

use soIT\LaravelSeeder\Containers\DataContainer;

interface ExecutorInterface
{
    public function addSource(SourceInterface $source);

    public function execute(DataContainer $data);

    public function proceedSources();

    public function seed();
}
