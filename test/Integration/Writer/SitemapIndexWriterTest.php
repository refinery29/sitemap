<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Refinery29\Sitemap\Test\Integration\Writer;

use Refinery29\Sitemap\Component\Sitemap;
use Refinery29\Sitemap\Component\SitemapIndex;
use Refinery29\Sitemap\Writer\SitemapIndexWriter;

class SitemapIndexWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testWriteCreatesXml()
    {
        $sitemap = new Sitemap('http://www.example.com/sitemap1.xml.gz');
        $sitemap = $sitemap->withLastModified(new \DateTimeImmutable('2004-10-01T18:23:17+00:00'));

        $anotherSitemap = new Sitemap('http://www.example.com/sitemap2.xml.gz');

        $index = new SitemapIndex([
            $sitemap,
            $anotherSitemap,
        ]);

        $expected = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>http://www.example.com/sitemap1.xml.gz</loc>
        <lastmod>2004-10-01T18:23:17+00:00</lastmod>
    </sitemap>
    <sitemap>
        <loc>http://www.example.com/sitemap2.xml.gz</loc>
    </sitemap>
</sitemapindex>
XML;

        $writer = new SitemapIndexWriter();

        $this->assertXmlStringEqualsXmlString($expected, $writer->write($index));
    }
}
