<?php

namespace Refinery29\Sitemap\Test\Writer\Video;

use DateTime;
use Refinery29\Sitemap\Component\Video\GalleryLocationInterface;
use Refinery29\Sitemap\Component\Video\PlatformInterface;
use Refinery29\Sitemap\Component\Video\PlayerLocationInterface;
use Refinery29\Sitemap\Component\Video\PriceInterface;
use Refinery29\Sitemap\Component\Video\RestrictionInterface;
use Refinery29\Sitemap\Component\Video\TagInterface;
use Refinery29\Sitemap\Component\Video\UploaderInterface;
use Refinery29\Sitemap\Component\Video\Video;
use Refinery29\Sitemap\Component\Video\VideoInterface;
use Refinery29\Sitemap\Test\Writer\AbstractTestCase;
use Refinery29\Sitemap\Writer\Video\GalleryLocationWriter;
use Refinery29\Sitemap\Writer\Video\PlatformWriter;
use Refinery29\Sitemap\Writer\Video\PlayerLocationWriter;
use Refinery29\Sitemap\Writer\Video\PriceWriter;
use Refinery29\Sitemap\Writer\Video\RestrictionWriter;
use Refinery29\Sitemap\Writer\Video\TagWriter;
use Refinery29\Sitemap\Writer\Video\UploaderWriter;
use Refinery29\Sitemap\Writer\Video\VideoWriter;
use XMLWriter;

class VideoWriterTest extends AbstractTestCase
{
    public function testConstructorCreatesRequiredWriters()
    {
        $writer = new VideoWriter();

        $this->assertAttributeInstanceOf(PlayerLocationWriter::class, 'playerLocationWriter', $writer);
        $this->assertAttributeInstanceOf(GalleryLocationWriter::class, 'galleryLocationWriter', $writer);
        $this->assertAttributeInstanceOf(RestrictionWriter::class, 'restrictionWriter', $writer);
        $this->assertAttributeInstanceOf(UploaderWriter::class, 'uploaderWriter', $writer);
        $this->assertAttributeInstanceOf(PlatformWriter::class, 'platformWriter', $writer);
        $this->assertAttributeInstanceOf(PriceWriter::class, 'priceWriter', $writer);
        $this->assertAttributeInstanceOf(TagWriter::class, 'tagWriter', $writer);
    }

    public function testWriteSimpleVideo()
    {
        $faker = $this->getFaker();

        $thumbnailLocation = $faker->url;
        $title = $faker->sentence;
        $description = $faker->paragraphs(5, true);

        $video = $this->getVideoMock(
            $thumbnailLocation,
            $title,
            $description
        );

        $xmlWriter = $this->getXmlWriterMock();

        $this->expectToStartElement($xmlWriter, 'video:video');

        $this->expectToWriteElement($xmlWriter, 'video:thumbnail_loc', $thumbnailLocation);
        $this->expectToWriteElement($xmlWriter, 'video:title', $title);
        $this->expectToWriteElement($xmlWriter, 'video:description', $description);

        $this->expectToEndElement($xmlWriter);

        $writer = new VideoWriter(
            $this->getPlayerLocationWriterMock(),
            $this->getGalleryLocationWriterMock(),
            $this->getRestrictionWriterMock(),
            $this->getUploaderWriterMock(),
            $this->getPlatformWriterMock(),
            $this->getPriceWriterMock(),
            $this->getTagWriterMock()
        );

        $writer->write($xmlWriter, $video);
    }

    public function testWriteAdvancedVideo()
    {
        $faker = $this->getFaker();

        $thumbnailLocation = $faker->url;
        $title = $faker->sentence;
        $description = $faker->paragraphs(5, true);
        $contentLocation = $faker->url;
        $playerLocation = $this->getPlayerLocationMock();
        $galleryLocation = $this->getGalleryLocationMock();
        $duration = $faker->numberBetween(
            Video::DURATION_LOWER_LIMIT,
            Video::DURATION_UPPER_LIMIT
        );
        $publicationDate = $faker->dateTime;
        $expirationDate = $faker->dateTime;
        $rating = $faker->randomFloat(1, 0, 5);
        $viewCount = $faker->randomNumber();
        $familyFriendly = Video::FAMILY_FRIENDLY_NO;
        $category = $faker->word;
        $restriction = $this->getRestrictionMock();
        $requiresSubscription = $faker->randomElement([
            Video::REQUIRES_SUBSCRIPTION_YES,
            Video::REQUIRES_SUBSCRIPTION_NO,
        ]);
        $uploader = $this->getUploaderMock();
        $platform = $this->getPlatformMock();
        $live = $faker->randomElement([
            Video::LIVE_NO,
            Video::LIVE_YES,
        ]);
        $prices = [
            $this->getPriceMock(),
            $this->getPriceMock(),
            $this->getPriceMock(),
        ];
        $tags = [
            $this->getTagMock(),
            $this->getTagMock(),
            $this->getTagMock(),
        ];

        $video = $this->getVideoMock(
            $thumbnailLocation,
            $title,
            $description,
            $contentLocation,
            $playerLocation,
            $galleryLocation,
            $duration,
            $publicationDate,
            $expirationDate,
            $rating,
            $viewCount,
            $familyFriendly,
            $category,
            $restriction,
            $requiresSubscription,
            $uploader,
            $platform,
            $live,
            $prices,
            $tags
        );

        $xmlWriter = $this->getXmlWriterMock();

        $this->expectToStartElement($xmlWriter, 'video:video');

        $this->expectToWriteElement($xmlWriter, 'video:thumbnail_loc', $thumbnailLocation);
        $this->expectToWriteElement($xmlWriter, 'video:title', $title);
        $this->expectToWriteElement($xmlWriter, 'video:description', $description);
        $this->expectToWriteElement($xmlWriter, 'video:content_loc', $contentLocation);
        $this->expectToWriteElement($xmlWriter, 'video:duration', $duration);
        $this->expectToWriteElement($xmlWriter, 'video:publication_date', $publicationDate->format('c'));
        $this->expectToWriteElement($xmlWriter, 'video:expiration_date', $expirationDate->format('c'));
        $this->expectToWriteElement($xmlWriter, 'video:rating', number_format($rating, 1));
        $this->expectToWriteElement($xmlWriter, 'video:view_count', $viewCount);
        $this->expectToWriteElement($xmlWriter, 'video:family_friendly', $familyFriendly);
        $this->expectToWriteElement($xmlWriter, 'video:category', $category);
        $this->expectToWriteElement($xmlWriter, 'video:requires_subscription', $requiresSubscription);
        $this->expectToWriteElement($xmlWriter, 'video:live', $live);

        $this->expectToEndElement($xmlWriter);

        $playerLocationWriter = $this->getPlayerLocationWriterSpy($xmlWriter, $playerLocation);
        $galleryLocationWriter = $this->getGalleryLocationWriterSpy($xmlWriter, $galleryLocation);
        $restrictionWriter = $this->getRestrictionWriterSpy($xmlWriter, $restriction);
        $uploaderWriter = $this->getUploaderWriterSpy($xmlWriter, $uploader);
        $platformWriter = $this->getPlatformWriterSpy($xmlWriter, $platform);
        $priceWriter = $this->getPriceWriterSpy($xmlWriter, $prices);
        $tagWriter = $this->getTagWriterSpy($xmlWriter, $tags);

        $writer = new VideoWriter(
            $playerLocationWriter,
            $galleryLocationWriter,
            $restrictionWriter,
            $uploaderWriter,
            $platformWriter,
            $priceWriter,
            $tagWriter
        );

        $writer->write($xmlWriter, $video);
    }

    /**
     * @param string                        $thumbnailLocation
     * @param string                        $title
     * @param string                        $description
     * @param string|null                   $contentLocation
     * @param PlayerLocationInterface|null  $playerLocation
     * @param GalleryLocationInterface|null $galleryLocation
     * @param int|null                      $duration
     * @param DateTime|null                 $publicationDate
     * @param DateTime|null                 $expirationDate
     * @param float|null                    $rating
     * @param int|null                      $viewCount
     * @param string|null                   $familyFriendly
     * @param string|null                   $category
     * @param RestrictionInterface|null     $restriction
     * @param string|null                   $requiresSubscription
     * @param UploaderInterface             $uploader
     * @param PlatformInterface|null        $platform
     * @param string|null                   $live
     * @param PriceInterface[]              $prices
     * @param TagInterface[]                $tags
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|VideoInterface
     *
     * @internal param null|UploaderInterface $video
     */
    private function getVideoMock(
        $thumbnailLocation,
        $title,
        $description,
        $contentLocation = null,
        PlayerLocationInterface $playerLocation = null,
        GalleryLocationInterface $galleryLocation = null,
        $duration = null,
        DateTime $publicationDate = null,
        DateTime $expirationDate = null,
        $rating = null,
        $viewCount = null,
        $familyFriendly = null,
        $category = null,
        RestrictionInterface $restriction = null,
        $requiresSubscription = null,
        UploaderInterface $uploader = null,
        PlatformInterface $platform = null,
        $live = null,
        array $prices = [],
        array $tags = []
    ) {
        $video = $this->getMockBuilder(VideoInterface::class)->getMock();

        $video
            ->expects($this->any())
            ->method('getThumbnailLocation')
            ->willReturn($thumbnailLocation)
        ;

        $video
            ->expects($this->any())
            ->method('getTitle')
            ->willReturn($title)
        ;

        $video
            ->expects($this->any())
            ->method('getDescription')
            ->willReturn($description)
        ;

        $video
            ->expects($this->any())
            ->method('getContentLocation')
            ->willReturn($contentLocation)
        ;

        $video
            ->expects($this->any())
            ->method('getPlayerLocation')
            ->willReturn($playerLocation)
        ;

        $video
            ->expects($this->any())
            ->method('getGalleryLocation')
            ->willReturn($galleryLocation)
        ;

        $video
            ->expects($this->any())
            ->method('getDuration')
            ->willReturn($duration)
        ;

        $video
            ->expects($this->any())
            ->method('getPublicationDate')
            ->willReturn($publicationDate)
        ;

        $video
            ->expects($this->any())
            ->method('getExpirationDate')
            ->willReturn($expirationDate)
        ;

        $video
            ->expects($this->any())
            ->method('getRating')
            ->willReturn($rating)
        ;

        $video
            ->expects($this->any())
            ->method('getViewCount')
            ->willReturn($viewCount)
        ;

        $video
            ->expects($this->any())
            ->method('getFamilyFriendly')
            ->willReturn($familyFriendly)
        ;

        $video
            ->expects($this->any())
            ->method('getCategory')
            ->willReturn($category)
        ;

        $video
            ->expects($this->any())
            ->method('getRestriction')
            ->willReturn($restriction)
        ;

        $video
            ->expects($this->any())
            ->method('getRequiresSubscription')
            ->willReturn($requiresSubscription)
        ;

        $video
            ->expects($this->any())
            ->method('getUploader')
            ->willReturn($uploader)
        ;

        $video
            ->expects($this->any())
            ->method('getPlatform')
            ->willReturn($platform)
        ;

        $video
            ->expects($this->any())
            ->method('getLive')
            ->willReturn($live)
        ;

        $video
            ->expects($this->any())
            ->method('getPrices')
            ->willReturn($prices)
        ;

        $video
            ->expects($this->any())
            ->method('getTags')
            ->willReturn($tags)
        ;

        return $video;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|GalleryLocationInterface
     */
    private function getGalleryLocationMock()
    {
        return $this->getMockBuilder(GalleryLocationInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|GalleryLocationWriter
     */
    private function getGalleryLocationWriterMock()
    {
        return $this->getMockBuilder(GalleryLocationWriter::class)->getMock();
    }

    /**
     * @param XMLWriter                $xmlWriter
     * @param GalleryLocationInterface $galleryLocation
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|GalleryLocationWriter
     */
    private function getGalleryLocationWriterSpy(XMLWriter $xmlWriter, GalleryLocationInterface $galleryLocation)
    {
        $galleryLocationWriter = $this->getGalleryLocationWriterMock();

        $galleryLocationWriter
            ->expects($this->once())
            ->method('write')
            ->with(
                $this->identicalTo($xmlWriter),
                $this->identicalTo($galleryLocation)
            )
        ;

        return $galleryLocationWriter;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|PlatformInterface
     */
    private function getPlatformMock()
    {
        return $this->getMockBuilder(PlatformInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|PlatformWriter
     */
    private function getPlatformWriterMock()
    {
        return $this->getMockBuilder(PlatformWriter::class)->getMock();
    }

    /**
     * @param XMLWriter         $xmlWriter
     * @param PlatformInterface $platform
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|PlatformWriter
     */
    private function getPlatformWriterSpy(XMLWriter $xmlWriter, PlatformInterface $platform)
    {
        $platformWriter = $this->getPlatformWriterMock();

        $platformWriter
            ->expects($this->once())
            ->method('write')
            ->with(
                $this->identicalTo($xmlWriter),
                $this->identicalTo($platform)
            )
        ;

        return $platformWriter;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|PlayerLocationInterface
     */
    private function getPlayerLocationMock()
    {
        return $this->getMockBuilder(PlayerLocationInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|PlayerLocationWriter
     */
    private function getPlayerLocationWriterMock()
    {
        return $this->getMockBuilder(PlayerLocationWriter::class)->getMock();
    }

    /**
     * @param XMLWriter               $xmlWriter
     * @param PlayerLocationInterface $playerLocation
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|PlayerLocationWriter
     */
    private function getPlayerLocationWriterSpy(XMLWriter $xmlWriter, PlayerLocationInterface $playerLocation)
    {
        $playerLocationWriter = $this->getPlayerLocationWriterMock();

        $playerLocationWriter
            ->expects($this->once())
            ->method('write')
            ->with(
                $this->identicalTo($xmlWriter),
                $this->identicalTo($playerLocation)
            )
        ;

        return $playerLocationWriter;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|PriceInterface
     */
    private function getPriceMock()
    {
        return $this->getMockBuilder(PriceInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|PriceWriter
     */
    private function getPriceWriterMock()
    {
        return $this->getMockBuilder(PriceWriter::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|PriceWriter
     */
    private function getPriceWriterSpy(XMLWriter $xmlWriter, array $prices)
    {
        $priceWriter = $this->getPriceWriterMock();

        foreach ($prices as $i => $price) {
            $priceWriter
                ->expects($this->at($i))
                ->method('write')
                ->with(
                    $this->identicalTo($xmlWriter),
                    $this->identicalTo($price)
                )
            ;
        }

        return $priceWriter;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|RestrictionInterface
     */
    private function getRestrictionMock()
    {
        return $this->getMockBuilder(RestrictionInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|RestrictionWriter
     */
    private function getRestrictionWriterMock()
    {
        return $restrictionWriter = $this->getMockBuilder(RestrictionWriter::class)->getMock();
    }

    /**
     * @param XMLWriter            $xmlWriter
     * @param RestrictionInterface $restriction
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|RestrictionWriter
     */
    private function getRestrictionWriterSpy(XMLWriter $xmlWriter, RestrictionInterface $restriction)
    {
        $restrictionWriter = $this->getRestrictionWriterMock();

        $restrictionWriter
            ->expects($this->once())
            ->method('write')
            ->with(
                $this->identicalTo($xmlWriter),
                $this->identicalTo($restriction)
            )
        ;

        return $restrictionWriter;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|TagInterface
     */
    private function getTagMock()
    {
        return $this->getMockBuilder(TagInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|TagWriter
     */
    private function getTagWriterMock()
    {
        return $this->getMockBuilder(TagWriter::class)->getMock();
    }

    /**
     * @param XMLWriter $xmlWriter
     * @param array     $tags
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|TagWriter
     */
    private function getTagWriterSpy(XMLWriter $xmlWriter, array $tags)
    {
        $tagWriter = $this->getTagWriterMock();

        foreach ($tags as $i => $tag) {
            $tagWriter
                ->expects($this->at($i))
                ->method('write')
                ->with(
                    $this->identicalTo($xmlWriter),
                    $this->identicalTo($tag)
                )
            ;
        }

        return $tagWriter;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|UploaderInterface
     */
    private function getUploaderMock()
    {
        return $this->getMockBuilder(UploaderInterface::class)->getMock();
    }

    /**
     * @param XMLWriter         $xmlWriter
     * @param UploaderInterface $uploader
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|UploaderWriter
     */
    private function getUploaderWriterSpy(XMLWriter $xmlWriter, UploaderInterface $uploader)
    {
        $uploaderWriter = $this->getUploaderWriterMock();

        $uploaderWriter
            ->expects($this->once())
            ->method('write')
            ->with(
                $this->identicalTo($xmlWriter),
                $this->identicalTo($uploader)
            )
        ;

        return $uploaderWriter;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|UploaderWriter
     */
    private function getUploaderWriterMock()
    {
        return $this->getMockBuilder(UploaderWriter::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|XMLWriter
     */
    private function getXmlWriterMock()
    {
        return $this->getMockBuilder(XMLWriter::class)->getMock();
    }
}
