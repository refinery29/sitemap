<?php

namespace Refinery29\Sitemap\Test\Writer\News;

use Refinery29\Sitemap\Component\News\PublicationInterface;
use Refinery29\Sitemap\Test\Writer\AbstractTestCase;
use Refinery29\Sitemap\Writer\News\PublicationWriter;
use XMLWriter;

class PublicationWriterTest extends AbstractTestCase
{
    public function testWritePublication()
    {
        $faker = $this->getFaker();

        $name = $faker->name;
        $language = $faker->languageCode;

        $publication = $this->getPublicationMock(
            $name,
            $language
        );

        $xmlWriter = $this->getXmlWriterMock();

        $this->startsElement($xmlWriter, 'news:publication');

        $this->writesElement($xmlWriter, 'news:name', $name);
        $this->writesElement($xmlWriter, 'news:language', $language);

        $this->endsElement($xmlWriter);

        $writer = new PublicationWriter();

        $writer->write($xmlWriter, $publication);
    }

    /**
     * @param string $name
     * @param string $language
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Refinery29\Sitemap\Component\News\PublicationInterface
     */
    private function getPublicationMock($name, $language)
    {
        $publication = $this->getMockBuilder(PublicationInterface::class)->getMock();

        $publication
            ->expects($this->any())
            ->method('getName')
            ->willReturn($name)
        ;

        $publication
            ->expects($this->any())
            ->method('getLanguage')
            ->willReturn($language)
        ;

        return $publication;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|XMLWriter
     */
    private function getXmlWriterMock()
    {
        return $this->getMockBuilder(XMLWriter::class)->getMock();
    }
}