<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */
namespace Refinery29\Sitemap\Test\Writer\Video;

use Refinery29\Sitemap\Component\Video\PlatformInterface;
use Refinery29\Sitemap\Test\Writer\AbstractTestCase;
use Refinery29\Sitemap\Writer\Video\PlatformWriter;
use XMLWriter;

class PlatformWriterTest extends AbstractTestCase
{
    public function testWritePlatform()
    {
        $faker = $this->getFaker();

        $relationship = $faker->randomElement([
            PlatformInterface::RELATIONSHIP_ALLOW,
            PlatformInterface::RELATIONSHIP_DENY,
        ]);

        $types = $faker->randomElements([
            PlatformInterface::TYPE_MOBILE,
            PlatformInterface::TYPE_TV,
            PlatformInterface::TYPE_WEB,
        ], 2);

        $platform = $this->getPlatformMock(
            $relationship,
            $types
        );

        $xmlWriter = $this->getXmlWriterMock();

        $this->expectToWriteElement($xmlWriter, 'video:platform', implode(' ', $types), [
            'relationship' => $relationship,
        ]);

        $writer = new PlatformWriter();

        $writer->write($platform, $xmlWriter);
    }

    /**
     * @param string $relationship
     * @param array  $types
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|PlatformInterface
     */
    private function getPlatformMock($relationship, array $types)
    {
        $platform = $this->getMockBuilder(PlatformInterface::class)->getMock();

        $platform
            ->expects($this->any())
            ->method('getRelationship')
            ->willReturn($relationship)
        ;

        $platform
            ->expects($this->any())
            ->method('getTypes')
            ->willReturn($types)
        ;

        return $platform;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|XMLWriter
     */
    private function getXmlWriterMock()
    {
        return $this->getMockBuilder(XMLWriter::class)->getMock();
    }
}
