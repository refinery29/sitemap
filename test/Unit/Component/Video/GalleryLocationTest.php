<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */
namespace Refinery29\Sitemap\Test\Unit\Component\Video;

use Refinery29\Sitemap\Component\Video\GalleryLocation;
use Refinery29\Sitemap\Component\Video\GalleryLocationInterface;
use Refinery29\Test\Util\Faker\GeneratorTrait;
use ReflectionClass;

class GalleryLocationTest extends \PHPUnit_Framework_TestCase
{
    use GeneratorTrait;

    public function testIsFinal()
    {
        $reflectionClass = new ReflectionClass(GalleryLocation::class);

        $this->assertTrue($reflectionClass->isFinal());
    }

    public function testImplementsGalleryLocationInterface()
    {
        $reflectionClass = new ReflectionClass(GalleryLocation::class);

        $this->assertTrue($reflectionClass->implementsInterface(GalleryLocationInterface::class));
    }

    public function testDefaults()
    {
        $galleryLocation = new GalleryLocation($this->getFaker()->url);

        $this->assertNull($galleryLocation->title());
    }

    public function testConstructorSetsValue()
    {
        $faker = $this->getFaker();

        $location = $faker->url;

        $galleryLocation = new GalleryLocation($location);

        $this->assertSame($location, $galleryLocation->location());
    }

    public function testWithTitleClonesObjectAndSetsValue()
    {
        $faker = $this->getFaker();

        $title = $faker->sentence;

        $galleryLocation = new GalleryLocation($faker->url);

        $instance = $galleryLocation->withTitle($title);

        $this->assertInstanceOf(GalleryLocation::class, $instance);
        $this->assertNotSame($galleryLocation, $instance);
        $this->assertSame($title, $instance->title());
    }
}