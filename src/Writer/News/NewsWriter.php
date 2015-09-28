<?php

namespace Refinery29\Sitemap\Writer\News;

use DateTime;
use Refinery29\Sitemap\Component\News\NewsInterface;
use XMLWriter;

/**
 * @link https://support.google.com/news/publisher/answer/74288?hl=en#exampleentry
 */
class NewsWriter
{
    /**
     * @var PublicationWriter
     */
    private $publicationWriter;

    /**
     * @param PublicationWriter $publicationWriter
     */
    public function __construct(PublicationWriter $publicationWriter)
    {
        $this->publicationWriter = $publicationWriter;
    }

    /**
     * @param XMLWriter     $xmlWriter
     * @param NewsInterface $news
     */
    public function write(XMLWriter $xmlWriter, NewsInterface $news)
    {
        $xmlWriter->startElement('news:news');

        $this->publicationWriter->write($xmlWriter, $news->getPublication());

        $this->writePublicationDate($xmlWriter, $news->getPublicationDate());
        $this->writeTitle($xmlWriter, $news->getTitle());
        $this->writeAccess($xmlWriter, $news->getAccess());
        $this->writeGenres($xmlWriter, $news->getGenres());
        $this->writeKeywords($xmlWriter, $news->getKeywords());
        $this->writeStockTickers($xmlWriter, $news->getStockTickers());

        $xmlWriter->endElement();
    }

    private function writePublicationDate(XMLWriter $xmlWriter, DateTime $publicationDate)
    {
        $xmlWriter->startElement('news:publication_date');
        $xmlWriter->text($publicationDate->format('c'));
        $xmlWriter->endElement();
    }

    private function writeTitle(XMLWriter $xmlWriter, $title)
    {
        $xmlWriter->startElement('news:title');
        $xmlWriter->text($title);
        $xmlWriter->endElement();
    }

    private function writeAccess(XMLWriter $xmlWriter, $access = null)
    {
        if ($access === null) {
            return;
        }

        $xmlWriter->startElement('news:access');
        $xmlWriter->text($access);
        $xmlWriter->endElement();
    }

    private function writeGenres(XMLWriter $xmlWriter, array $genres)
    {
        if (count($genres) === 0) {
            return;
        }

        $xmlWriter->startElement('news:genres');
        $xmlWriter->text(implode(', ', $genres));
        $xmlWriter->endElement();
    }

    private function writeKeywords(XMLWriter $xmlWriter, array $keywords)
    {
        if (count($keywords) === 0) {
            return;
        }

        $xmlWriter->startElement('news:keywords');
        $xmlWriter->text(implode(', ', $keywords));
        $xmlWriter->endElement();
    }

    private function writeStockTickers(XMLWriter $xmlWriter, array $stockTickers)
    {
        if (count($stockTickers) === 0) {
            return;
        }

        $xmlWriter->startElement('news:stock_tickers');
        $xmlWriter->text(implode(', ', $stockTickers));
        $xmlWriter->endElement();
    }
}
