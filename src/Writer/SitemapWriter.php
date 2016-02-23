<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */
namespace Refinery29\Sitemap\Writer;

use DateTimeInterface;
use Refinery29\Sitemap\Component\SitemapInterface;
use XMLWriter;

/**
 * @link https://support.google.com/webmasters/answer/75712?rd=1
 */
class SitemapWriter
{
    public function write(SitemapInterface $sitemap, XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('sitemap');

        $this->writeLocation($xmlWriter, $sitemap->getLocation());
        $this->writeLastModified($xmlWriter, $sitemap->getLastModified());

        $xmlWriter->endElement();
    }

    private function writeLocation(XMLWriter $xmlWriter, $location)
    {
        $xmlWriter->startElement('loc');
        $xmlWriter->text($location);
        $xmlWriter->endElement();
    }

    private function writeLastModified(XMLWriter $xmlWriter, DateTimeInterface $lastModified = null)
    {
        if ($lastModified === null) {
            return;
        }

        $xmlWriter->startElement('lastmod');
        $xmlWriter->text($lastModified->format('c'));
        $xmlWriter->endElement();
    }
}
