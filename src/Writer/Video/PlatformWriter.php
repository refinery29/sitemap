<?php

namespace Refinery29\Sitemap\Writer\Video;

use Refinery29\Sitemap\Component\Video\PlatformInterface;
use XMLWriter;

/**
 * @link https://developers.google.com/webmasters/videosearch/sitemaps#video-sitemap-tag-definitions
 */
class PlatformWriter
{
    /**
     * @param XMLWriter         $xmlWriter
     * @param PlatformInterface $platform
     */
    public function write(XMLWriter $xmlWriter, PlatformInterface $platform)
    {
        $xmlWriter->startElement('video:platform');
        $xmlWriter->writeAttribute('relationship', $platform->getRelationship());
        $xmlWriter->text(implode(' ', $platform->getTypes()));
        $xmlWriter->endElement();
    }
}