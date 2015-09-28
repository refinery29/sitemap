<?php

namespace Refinery29\Sitemap\Test\Component;

use Refinery29\Sitemap\Component\UrlInterface;
use Refinery29\Sitemap\Component\UrlSet;
use Refinery29\Sitemap\Test\Util\FakerTrait;

class UrlSetTest extends \PHPUnit_Framework_TestCase
{
    use FakerTrait;

    public function testConstants()
    {
        $this->assertSame('xmlns', UrlSet::XML_NAMESPACE_ATTRIBUTE);
        $this->assertSame('http://www.sitemaps.org/schemas/sitemap/0.9', UrlSet::XML_NAMESPACE_URI);
    }

    public function testDefaults()
    {
        $urlSet = new UrlSet();

        $this->assertInternalType('array', $urlSet->getUrls());
        $this->assertCount(0, $urlSet->getUrls());
    }

    public function testCanAddUrl()
    {
        $url = $this->getUrlMock();

        $urlSet = new UrlSet();
        $urlSet->addUrl($url);

        $this->assertInternalType('array', $urlSet->getUrls());
        $this->assertCount(1, $urlSet->getUrls());
        $this->assertSame($url, $urlSet->getUrls()[0]);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|UrlInterface
     */
    private function getUrlMock()
    {
        return $this->getMockBuilder(UrlInterface::class)->getMock();
    }
}
