<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Refinery29\Sitemap\Test\Unit\Writer\Image;

use Refinery29\Sitemap\Component\Image\ImageInterface;
use Refinery29\Sitemap\Test\Unit\Writer\AbstractTestCase;
use Refinery29\Sitemap\Writer\Image\ImageWriter;

class ImageWriterTest extends AbstractTestCase
{
    public function testWriteSimpleImage()
    {
        $faker = $this->getFaker();

        $location = $faker->url;

        $image = $this->getImageMock($location);

        $xmlWriter = $this->getXmlWriterMock();

        $this->expectToStartElement($xmlWriter, 'image:image');

        $this->expectToWriteElement($xmlWriter, 'image:loc', $location);

        $this->expectToEndElement($xmlWriter);

        $writer = new ImageWriter();

        $writer->write($image, $xmlWriter);
    }

    public function testWriteAdvancedImage()
    {
        $faker = $this->getFaker();

        $location = $faker->url;
        $title = $faker->sentence;
        $caption = $faker->paragraph;
        $geoLocation = $faker->address;
        $licence = $faker->url;

        $image = $this->getImageMock(
            $location,
            $title,
            $caption,
            $geoLocation,
            $licence
        );

        $xmlWriter = $this->getXmlWriterMock();

        $this->expectToStartElement($xmlWriter, 'image:image');

        $this->expectToWriteElement($xmlWriter, 'image:loc', $location);
        $this->expectToWriteElement($xmlWriter, 'image:title', $title);
        $this->expectToWriteElement($xmlWriter, 'image:caption', $caption);
        $this->expectToWriteElement($xmlWriter, 'image:geo_location', $geoLocation);
        $this->expectToWriteElement($xmlWriter, 'image:licence', $licence);

        $this->expectToEndElement($xmlWriter);

        $writer = new ImageWriter();

        $writer->write($image, $xmlWriter);
    }

    /**
     * @param string      $location
     * @param string|null $title
     * @param string|null $caption
     * @param string|null $geoLocation
     * @param string|null $licence
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ImageInterface
     */
    private function getImageMock($location, $title = null, $caption = null, $geoLocation = null, $licence = null)
    {
        $image = $this->getMock(ImageInterface::class);

        $image
            ->expects($this->any())
            ->method('location')
            ->willReturn($location);

        $image
            ->expects($this->any())
            ->method('title')
            ->willReturn($title);

        $image
            ->expects($this->any())
            ->method('caption')
            ->willReturn($caption);

        $image
            ->expects($this->any())
            ->method('geoLocation')
            ->willReturn($geoLocation);

        $image
            ->expects($this->any())
            ->method('licence')
            ->willReturn($licence);

        return $image;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\XMLWriter
     */
    private function getXmlWriterMock()
    {
        return $this->getMock(\XMLWriter::class);
    }
}
