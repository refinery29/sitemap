<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */
namespace Refinery29\Sitemap\Test\Unit\Component\Video;

use InvalidArgumentException;
use Refinery29\Sitemap\Component\Video\PlayerLocation;
use Refinery29\Sitemap\Component\Video\PlayerLocationInterface;
use Refinery29\Test\Util\Faker\GeneratorTrait;
use ReflectionClass;

class PlayerLocationTest extends \PHPUnit_Framework_TestCase
{
    use GeneratorTrait;

    public function testConstants()
    {
        $this->assertSame('yes', PlayerLocationInterface::ALLOW_EMBED_YES);
        $this->assertSame('no', PlayerLocationInterface::ALLOW_EMBED_NO);
    }

    public function testIsFinal()
    {
        $reflectionClass = new ReflectionClass(PlayerLocation::class);

        $this->assertTrue($reflectionClass->isFinal());
    }

    public function testImplementsPlayerLocationInterface()
    {
        $reflectionClass = new ReflectionClass(PlayerLocation::class);

        $this->assertTrue($reflectionClass->implementsInterface(PlayerLocationInterface::class));
    }

    public function testDefaults()
    {
        $playerLocation = new PlayerLocation($this->getFaker()->url);

        $this->assertNull($playerLocation->allowEmbed());
        $this->assertNull($playerLocation->autoPlay());
    }

    public function testConstructorSetsValue()
    {
        $faker = $this->getFaker();

        $location = $faker->url;

        $playerLocation = new PlayerLocation($location);

        $this->assertSame($location, $playerLocation->location());
    }

    public function testWithAllowEmbedRejectsInvalidValues()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $faker = $this->getFaker();

        $allowEmbed = $faker->word;

        $playerLocation = new PlayerLocation($faker->url);

        $playerLocation->withAllowEmbed($allowEmbed);
    }

    public function testWithAllowEmbedClonesObjectAndSetsValue()
    {
        $faker = $this->getFaker();

        $allowEmbed = $faker->randomElement([
            PlayerLocationInterface::ALLOW_EMBED_NO,
            PlayerLocationInterface::ALLOW_EMBED_YES,
        ]);

        $playerLocation = new PlayerLocation($faker->url);

        $instance = $playerLocation->withAllowEmbed($allowEmbed);

        $this->assertInstanceOf(PlayerLocation::class, $instance);
        $this->assertNotSame($playerLocation, $instance);
        $this->assertSame($allowEmbed, $instance->allowEmbed());
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
