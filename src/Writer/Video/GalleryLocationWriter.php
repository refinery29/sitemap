<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */
namespace Refinery29\Sitemap\Writer\Video;

use Refinery29\Sitemap\Component\Video\GalleryLocationInterface;
use XMLWriter;

/**
 * @link https://developers.google.com/webmasters/videosearch/sitemaps#video-sitemap-tag-definitions
 */
class GalleryLocationWriter
{
    /**
     * @param XMLWriter                $xmlWriter
     * @param GalleryLocationInterface $galleryLocation
     */
    public function write(XMLWriter $xmlWriter, GalleryLocationInterface $galleryLocation)
    {
        $xmlWriter->startElement('video:gallery_loc');

        if ($galleryLocation->getTitle()) {
            $xmlWriter->writeAttribute('title', $galleryLocation->getTitle());
        }

        $xmlWriter->text($galleryLocation->getLocation());
        $xmlWriter->endElement();
    }
}
