<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Refinery29\Sitemap\Test\Unit\Writer\Video;

use Refinery29\Sitemap\Component\Video\UploaderInterface;
use Refinery29\Sitemap\Test\Unit\Writer\AbstractTestCase;
use Refinery29\Sitemap\Writer\Video\UploaderWriter;

final class UploaderWriterTest extends AbstractTestCase
{
    public function testWriteWithSimpleUploader()
    {
        $name = $this->getFaker()->name;

        $uploader = $this->getUploaderMock($name);

        $xmlWriter = $this->getXmlWriterMock();

        $this->expectToWriteElement($xmlWriter, 'video:uploader', $name);

        $writer = new UploaderWriter();

        $writer->write($uploader, $xmlWriter);
    }

    public function testWriteWithAdvancedUploader()
    {
        $faker = $this->getFaker();

        $name = $faker->name;
        $info = $faker->url;

        $uploader = $this->getUploaderMock(
            $name,
            $info
        );

        $xmlWriter = $this->getXmlWriterMock();

        $this->expectToWriteElement($xmlWriter, 'video:uploader', $name, [
            'info' => $info,
        ]);

        $writer = new UploaderWriter();

        $writer->write($uploader, $xmlWriter);
    }

    /**
     * @param string      $name
     * @param string|null $info
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|UploaderInterface
     */
    private function getUploaderMock($name, $info = null)
    {
        $uploader = $this->getMock(UploaderInterface::class);

        $uploader
            ->expects($this->any())
            ->method('name')
            ->willReturn($name);

        $uploader
            ->expects($this->any())
            ->method('info')
            ->willReturn($info);

        return $uploader;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\XMLWriter
     */
    private function getXmlWriterMock()
    {
        return $this->getMock(\XMLWriter::class);
    }
}
