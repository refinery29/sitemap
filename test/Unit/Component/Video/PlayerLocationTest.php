<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Refinery29\Sitemap\Test\Unit\Component\Video;

use Refinery29\Sitemap\Component\Video\PlayerLocation;
use Refinery29\Sitemap\Component\Video\PlayerLocationInterface;
use Refinery29\Test\Util\Faker\GeneratorTrait;

final class PlayerLocationTest extends \PHPUnit_Framework_TestCase
{
    use GeneratorTrait;

    public function testConstants()
    {
        $this->assertSame('yes', PlayerLocationInterface::ALLOW_EMBED_YES);
        $this->assertSame('no', PlayerLocationInterface::ALLOW_EMBED_NO);
    }

    public function testIsFinal()
    {
        $reflectionClass = new \ReflectionClass(PlayerLocation::class);

        $this->assertTrue($reflectionClass->isFinal());
    }

    public function testImplementsPlayerLocationInterface()
    {
        $reflectionClass = new \ReflectionClass(PlayerLocation::class);

        $this->assertTrue($reflectionClass->implementsInterface(PlayerLocationInterface::class));
    }

    public function testDefaults()
    {
        $playerLocation = new PlayerLocation($this->getFaker()->url);

        $this->assertNull($playerLocation->allowEmbed());
        $this->assertNull($playerLocation->autoPlay());
    }

    /**
     * @dataProvider Refinery29\Test\Util\DataProvider\InvalidUrl::data()
     *
     * @param mixed $location
     */
    public function testConstructorRejectsInvalidLocation($location)
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        new PlayerLocation($location);
    }

    public function testConstructorSetsValue()
    {
        $faker = $this->getFaker();

        $location = $faker->url;

        $playerLocation = new PlayerLocation($location);

        $this->assertSame($location, $playerLocation->location());
    }

    /**
     * @dataProvider Refinery29\Test\Util\DataProvider\InvalidString::data()
     *
     * @param $allowEmbed
     */
    public function testWithAllowEmbedRejectsInvalidValues($allowEmbed)
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $faker = $this->getFaker();

        $playerLocation = new PlayerLocation($faker->url);

        $playerLocation->withAllowEmbed($allowEmbed);
    }

    public function testWithAllowEmbedRejectsUnknownValues()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $faker = $this->getFaker();

        $allowEmbed = $faker->sentence();

        $playerLocation = new PlayerLocation($faker->url);

        $playerLocation->withAllowEmbed($allowEmbed);
    }

    /**
     * @dataProvider providerAllowEmbed
     *
     * @param string $allowEmbed
     */
    public function testWithAllowEmbedClonesObjectAndSetsValue($allowEmbed)
    {
        $faker = $this->getFaker();

        $playerLocation = new PlayerLocation($faker->url);

        $instance = $playerLocation->withAllowEmbed($allowEmbed);

        $this->assertInstanceOf(PlayerLocation::class, $instance);
        $this->assertNotSame($playerLocation, $instance);
        $this->assertSame($allowEmbed, $instance->allowEmbed());
    }

    /**
     * @return \Generator
     */
    public function providerAllowEmbed()
    {
        $values = [
            PlayerLocationInterface::ALLOW_EMBED_NO,
            PlayerLocationInterface::ALLOW_EMBED_YES,
        ];

        foreach ($values as $value) {
            yield [
                $value,
            ];
        }
    }

    /**
     * @dataProvider Refinery29\Test\Util\DataProvider\InvalidString::data()
     *
     * @param mixed $autoPlay
     */
    public function testWithAutoPlayRejectsInvalidValue($autoPlay)
    {
        $faker = $this->getFaker();

        $playerLocation = new PlayerLocation($faker->url);

        $instance = $playerLocation->withAutoPlay($autoPlay);

        $this->assertInstanceOf(PlayerLocation::class, $instance);
        $this->assertNotSame($playerLocation, $instance);
        $this->assertSame($autoPlay, $instance->autoPlay());
    }

    public function testWithAutoPlayClonesObjectAndSetsValue()
    {
        $faker = $this->getFaker();

        $autoPlay = implode('=', $faker->words(2));

        $playerLocation = new PlayerLocation($faker->url);

        $instance = $playerLocation->withAutoPlay($autoPlay);

        $this->assertInstanceOf(PlayerLocation::class, $instance);
        $this->assertNotSame($playerLocation, $instance);
        $this->assertSame($autoPlay, $instance->autoPlay());
    }
}
