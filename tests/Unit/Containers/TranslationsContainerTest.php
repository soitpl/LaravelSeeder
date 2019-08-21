<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Containers;


use PHPUnit\Framework\TestCase;

class TranslationsContainerTest extends TestCase
{
    private const TEST_PROP_T = 'test_p_t';
    private const TEST_PROP_S= 'test_p_s';

    public function testAddGet()
    {
        $container = new TranslationsContainer();
        $container->add(self::TEST_PROP_T, self::TEST_PROP_S);

        $this->assertEquals(self::TEST_PROP_T, $container->get(self::TEST_PROP_S));
        $container->add(self::TEST_PROP_S, self::TEST_PROP_T);

        $this->assertEquals(self::TEST_PROP_T, $container->get(self::TEST_PROP_S));
        $this->assertEquals(self::TEST_PROP_S, $container->get(self::TEST_PROP_T));
    }
}
