<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Refinery29\Sitemap\Test\Unit\Component;

use InvalidArgumentException;
use Refinery29\Sitemap\Component\SitemapIndex;
use Refinery29\Sitemap\Component\SitemapIndexInterface;
use Refinery29\Sitemap\Component\SitemapInterface;
use Refinery29\Test\Util\Faker\GeneratorTrait;
use ReflectionClass;
use stdClass;

class SitemapIndexTest extends \PHPUnit_Framework_TestCase
{
    use GeneratorTrait;

    public function testIsFinal()
    {
        $reflectionClass = new ReflectionClass(SitemapIndex::class);

        $this->assertTrue($reflectionClass->isFinal());
    }

    public function testImplementsSitemapIndexInterface()
    {
        $reflectionClass = new ReflectionClass(SitemapIndex::class);

        $this->assertTrue($reflectionClass->implementsInterface(SitemapIndexInterface::class));
    }

    /**
     * @dataProvider providerInvalidSitemaps
     *
     * @param mixed $value
     */
    public function testConstructorRejectsInvalidValue($value)
    {
        $this->setExpectedException(InvalidArgumentException::class);

        new SitemapIndex($value);
    }

    /**
     * @return \Generator
     */
    public function providerInvalidSitemaps()
    {
        $faker = $this->getFaker();

        $location = $faker->url;

        $values = [
            $faker->words(),
            [
                $this->getSitemapMock(),
                $this->getSitemapMock(),
                new stdClass(),
            ],
            [
                $this->getSitemapMock($location),
                $this->getSitemapMock($location),
            ],

        ];

        foreach ($values as $value) {
            yield [
                $value,
            ];
        }
    }

    public function testConstructorSetsValue()
    {
        $sitemaps = [
            $this->getSitemapMock(),
            $this->getSitemapMock(),
            $this->getSitemapMock(),
        ];

        $sitemapIndex = new SitemapIndex($sitemaps);

        $this->assertSame($sitemaps, $sitemapIndex->sitemaps());
    }

    /**
     * @param string|null $location
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|SitemapInterface
     */
    private function getSitemapMock($location = null)
    {
        $location = $location ?: $this->getFaker()->unique()->url;

        $sitemap = $this->getMockBuilder(SitemapInterface::class)->getMock();

        $sitemap
            ->expects($this->any())
            ->method('location')
            ->willReturn($location)
        ;

        return $sitemap;
    }
}
