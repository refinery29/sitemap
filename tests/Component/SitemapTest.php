<?php

namespace Refinery29\Sitemap\Test\Component;

use Refinery29\Sitemap\Component\Sitemap;
use Refinery29\Sitemap\Component\SitemapInterface;
use Refinery29\Sitemap\Test\Util\FakerTrait;

class SitemapTest extends \PHPUnit_Framework_TestCase
{
    use FakerTrait;

    public function testImplementsInterface()
    {
        $sitemap = new Sitemap($this->getFaker()->url);

        $this->assertInstanceOf(SitemapInterface::class, $sitemap);
    }

    public function testConstructorSetsValues()
    {
        $faker = $this->getFaker();

        $location = $faker->url;
        $lastModified = $faker->dateTime;

        $sitemap = new Sitemap(
            $location,
            $lastModified
        );

        $this->assertSame($location, $sitemap->getLocation());
        $this->assertEquals($lastModified, $sitemap->getLastModified());
        $this->assertNotSame($lastModified, $sitemap->getLastModified());
    }

    public function testDefaults()
    {
        $location = $this->getFaker()->url;

        $sitemap = new Sitemap($location);

        $this->assertSame($location, $sitemap->getLocation());
        $this->assertNull($sitemap->getLastModified());
    }
}
