<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Containers;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use soIT\LaravelSeeder\Containers\AdditionalPropertiesContainer;
use soIT\LaravelSeeders\Transformations\CallableTransformation;

class AdditionalPropertiesContainerTest extends TestCase
{
    public function testAssignCallback()
    {
        $faker = Factory::create();

        $instance = new AdditionalPropertiesContainer();

        $value = $faker->word;
        $instance->assignCallback($property = $faker->word, function () use ($value) {
            return $value;
        });

        $this->assertInstanceOf(CallableTransformation::class, $instance[$property]);
        $this->assertEquals($value, $instance[$property]->transform());
    }

    public function testAssignValueWithWord()
    {
        $faker = Factory::create();

        $instance = new AdditionalPropertiesContainer();

        $instance->assignValue($property = $faker->word, $value = $faker->word);

        $this->assertEquals($value, $instance[$property]);
    }
}
