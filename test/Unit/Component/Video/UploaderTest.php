<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Refinery29\Sitemap\Test\Unit\Component\Video;

use InvalidArgumentException;
use Refinery29\Sitemap\Component\Video\Uploader;
use Refinery29\Sitemap\Component\Video\UploaderInterface;
use Refinery29\Test\Util\Faker\GeneratorTrait;
use ReflectionClass;

class UploaderTest extends \PHPUnit_Framework_TestCase
{
    use GeneratorTrait;

    public function testIsFinal()
    {
        $reflectionClass = new ReflectionClass(Uploader::class);

        $this->assertTrue($reflectionClass->isFinal());
    }

    public function testImplementsUploaderInterface()
    {
        $reflectionClass = new ReflectionClass(Uploader::class);

        $this->assertTrue($reflectionClass->implementsInterface(UploaderInterface::class));
    }

    public function testDefaults()
    {
        $uploader = new Uploader($this->getFaker()->name);

        $this->assertNull($uploader->info());
    }

    /**
     * @dataProvider Refinery29\Test\Util\DataProvider\InvalidString::data()
     *
     * @param mixed $name
     */
    public function testConstructorRejectsInvalidName($name)
    {
        $this->setExpectedException(InvalidArgumentException::class);

        new Uploader($name);
    }

    public function testConstructorSetsValue()
    {
        $faker = $this->getFaker();

        $name = $faker->name;

        $uploader = new Uploader($name);

        $this->assertSame($name, $uploader->name());
    }

    /**
     * @dataProvider Refinery29\Test\Util\DataProvider\InvalidString::data()
     *
     * @param mixed $info
     */
    public function testWithInfoRejectsInvalidInfo($info)
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $uploader = new Uploader($this->getFaker()->url);

        $uploader->withInfo($info);
    }

    public function testWithInfoClonesObjectAndSetsValue()
    {
        $faker = $this->getFaker();

        $info = $faker->sentence;

        $uploader = new Uploader($faker->url);

        $instance = $uploader->withInfo($info);

        $this->assertInstanceOf(Uploader::class, $instance);
        $this->assertNotSame($uploader, $instance);
        $this->assertSame($info, $instance->info());
    }
}
