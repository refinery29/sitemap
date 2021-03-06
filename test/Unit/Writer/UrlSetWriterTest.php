<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Refinery29\Sitemap\Test\Unit\Writer;

use Refinery29\Sitemap\Component\Image\ImageInterface;
use Refinery29\Sitemap\Component\News\NewsInterface;
use Refinery29\Sitemap\Component\UrlInterface;
use Refinery29\Sitemap\Component\UrlSetInterface;
use Refinery29\Sitemap\Component\Video\VideoInterface;
use Refinery29\Sitemap\Writer\UrlSetWriter;
use Refinery29\Sitemap\Writer\UrlWriter;

final class UrlSetWriterTest extends AbstractTestCase
{
    public function testConstructorCreatesRequiredWriter()
    {
        $writer = new UrlSetWriter();

        $this->assertAttributeInstanceOf(UrlWriter::class, 'urlWriter', $writer);
    }

    public function testWriteUrlSet()
    {
        $output = $this->getFaker()->text();

        $urls = [
            $this->getUrlMock(),
            $this->getUrlMock(),
            $this->getUrlMock(),
        ];

        $urlSet = $this->getUrlSetMock($urls);

        $xmlWriter = $this->getXmlWriterMock();

        $this->expectToOpenMemory($xmlWriter);
        $this->expectToStartDocument($xmlWriter);
        $this->expectToStartElement($xmlWriter, 'urlset');

        $this->expectToWriteAttribute($xmlWriter, UrlSetInterface::XML_NAMESPACE_ATTRIBUTE, UrlSetInterface::XML_NAMESPACE_URI);
        $this->expectToWriteAttribute($xmlWriter, ImageInterface::XML_NAMESPACE_ATTRIBUTE, ImageInterface::XML_NAMESPACE_URI);
        $this->expectToWriteAttribute($xmlWriter, NewsInterface::XML_NAMESPACE_ATTRIBUTE, NewsInterface::XML_NAMESPACE_URI);
        $this->expectToWriteAttribute($xmlWriter, VideoInterface::XML_NAMESPACE_ATTRIBUTE, VideoInterface::XML_NAMESPACE_URI);

        $urlWriter = $this->getUrlWriterMock();

        foreach ($urls as $i => $url) {
            $urlWriter
                ->expects($this->at($i))
                ->method('write')
                ->with(
                    $this->identicalTo($url),
                    $this->identicalTo($xmlWriter)
                );
        }

        $this->expectToEndElement($xmlWriter);
        $this->expectToEndDocument($xmlWriter);
        $this->expectToOutput($xmlWriter, $output);

        $writer = new UrlSetWriter($urlWriter);

        $this->assertSame($output, $writer->write($urlSet, $xmlWriter));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|UrlInterface
     */
    private function getUrlMock()
    {
        return $this->createMock(UrlInterface::class);
    }

    /**
     * @param UrlInterface[] $urls
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|UrlSetInterface
     */
    private function getUrlSetMock(array $urls = [])
    {
        $urlSet = $this->createMock(UrlSetInterface::class);

        $urlSet
            ->expects($this->any())
            ->method('urls')
            ->willReturn($urls);

        return $urlSet;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|UrlWriter
     */
    private function getUrlWriterMock()
    {
        return $this->createMock(UrlWriter::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\XMLWriter
     */
    private function getXmlWriterMock()
    {
        return $this->createMock(\XMLWriter::class);
    }
}
