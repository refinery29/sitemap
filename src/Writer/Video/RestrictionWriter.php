<?php

namespace Refinery29\Sitemap\Writer\Video;

use Refinery29\Sitemap\Component\Video\RestrictionInterface;
use XMLWriter;

/**
 * @link https://developers.google.com/webmasters/videosearch/sitemaps#video-sitemap-tag-definitions
 */
class RestrictionWriter
{
    /**
     * @param XMLWriter            $xmlWriter
     * @param RestrictionInterface $restriction
     */
    public function write(XMLWriter $xmlWriter, RestrictionInterface $restriction)
    {
        $xmlWriter->startElement('video:restriction');
        $xmlWriter->writeAttribute('relationship', $restriction->getRelationship());
        $xmlWriter->text(implode(' ', $restriction->getCountryCodes()));
        $xmlWriter->endElement();
    }
}