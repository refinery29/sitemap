<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Refinery29\Sitemap\Test\Unit\Component\News;

use Refinery29\Sitemap\Component\News\News;
use Refinery29\Sitemap\Component\News\NewsInterface;
use Refinery29\Sitemap\Component\News\PublicationInterface;
use Refinery29\Test\Util\TestHelper;

final class NewsTest extends \PHPUnit_Framework_TestCase
{
    use TestHelper;

    public function testIsFinal()
    {
        $this->assertFinal(News::class);
    }

    public function testImplementsNewsInterface()
    {
        $this->assertImplements(NewsInterface::class, News::class);
    }

    public function testDefaults()
    {
        $faker = $this->getFaker();

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $this->assertNull($news->access());

        $this->assertInternalType('array', $news->genres());
        $this->assertCount(0, $news->genres());

        $this->assertInternalType('array', $news->keywords());
        $this->assertCount(0, $news->keywords());

        $this->assertInternalType('array', $news->stockTickers());
        $this->assertCount(0, $news->stockTickers());
    }

    /**
     * @dataProvider \Refinery29\Test\Util\DataProvider\InvalidString::data()
     *
     * @param mixed $title
     */
    public function testConstructorRejectsInvalidTitle($title)
    {
        $this->expectException(\InvalidArgumentException::class);

        $faker = $this->getFaker();

        new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $title
        );
    }

    /**
     * @dataProvider \Refinery29\Test\Util\DataProvider\BlankString::data()
     *
     * @param mixed $title
     */
    public function testConstructorRejectsBlankTitle($title)
    {
        $this->expectException(\InvalidArgumentException::class);

        $faker = $this->getFaker();

        new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $title
        );
    }

    public function testConstructorSetsValues()
    {
        $faker = $this->getFaker();

        $publication = $this->getPublicationMock();
        $publicationDate = $faker->dateTime;
        $title = $faker->sentence();

        $news = new News(
            $publication,
            $publicationDate,
            $title
        );

        $this->assertSame($publication, $news->publication());
        $this->assertNotSame($publicationDate, $news->publicationDate());
        $this->assertSame($title, $news->title());
    }

    /**
     * @dataProvider \Refinery29\Test\Util\DataProvider\InvalidString::data()
     *
     * @param mixed $access
     */
    public function testWithAccessRejectsInvalidValue($access)
    {
        $this->expectException(\InvalidArgumentException::class);

        $faker = $this->getFaker();

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $news->withAccess($access);
    }

    /**
     * @dataProvider providerAccess
     *
     * @param string $access
     */
    public function testWithAccessClonesObjectAndSetsValue($access)
    {
        $faker = $this->getFaker();

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $instance = $news->withAccess($access);

        $this->assertInstanceOf(News::class, $instance);
        $this->assertNotSame($news, $instance);
        $this->assertSame($access, $instance->access());
    }

    /**
     * @return \Generator
     */
    public function providerAccess()
    {
        return $this->provideData([
            NewsInterface::ACCESS_REGISTRATION,
            NewsInterface::ACCESS_SUBSCRIPTION,
        ]);
    }

    /**
     * @dataProvider \Refinery29\Test\Util\DataProvider\InvalidString::data()
     *
     * @param mixed $genre
     */
    public function testWithGenresRejectsInvalidValues($genre)
    {
        $this->expectException(\InvalidArgumentException::class);

        $faker = $this->getFaker();

        $genres = [
            NewsInterface::GENRE_BLOG,
            NewsInterface::GENRE_OP_ED,
            $genre,
        ];

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $news->withGenres($genres);
    }

    public function testWithGenresRejectsUnknownValues()
    {
        $this->expectException(\InvalidArgumentException::class);

        $faker = $this->getFaker();

        $genres = [
            NewsInterface::GENRE_BLOG,
            NewsInterface::GENRE_OP_ED,
            $faker->sentence(),
        ];

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $news->withGenres($genres);
    }

    /**
     * @dataProvider providerGenre
     *
     * @param string $genre
     */
    public function testWithGenresRejectsDuplicateValues($genre)
    {
        $this->expectException(\InvalidArgumentException::class);

        $faker = $this->getFaker();

        $genres = [
            $genre,
            $genre,
        ];

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $news->withGenres($genres);
    }

    public function testWithGenresClonesObjectAndSetsValue()
    {
        $faker = $this->getFaker();

        $genres = $faker->randomElements([
            NewsInterface::GENRE_BLOG,
            NewsInterface::GENRE_OP_ED,
            NewsInterface::GENRE_OPINION,
            NewsInterface::GENRE_SATIRE,
            NewsInterface::GENRE_USER_GENERATED,
        ]);

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $instance = $news->withGenres($genres);

        $this->assertInstanceOf(News::class, $instance);
        $this->assertNotSame($news, $instance);
        $this->assertSame($genres, $instance->genres());
    }

    /**
     * @return \Generator
     */
    public function providerGenre()
    {
        return $this->provideData([
            NewsInterface::GENRE_BLOG,
            NewsInterface::GENRE_OP_ED,
            NewsInterface::GENRE_OPINION,
            NewsInterface::GENRE_SATIRE,
            NewsInterface::GENRE_USER_GENERATED,
        ]);
    }

    /**
     * @dataProvider \Refinery29\Test\Util\DataProvider\InvalidString::data()
     *
     * @param mixed $keyword
     */
    public function testWithKeywordsRejectsInvalidValues($keyword)
    {
        $this->expectException(\InvalidArgumentException::class);

        $faker = $this->getFaker();

        $keywords = [
            $faker->word,
            $faker->word,
            $keyword,
        ];

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $news->withKeywords($keywords);
    }

    /**
     * @dataProvider \Refinery29\Test\Util\DataProvider\BlankString::data()
     *
     * @param mixed $keyword
     */
    public function testWithKeywordsRejectsBlankValues($keyword)
    {
        $this->expectException(\InvalidArgumentException::class);

        $faker = $this->getFaker();

        $keywords = [
            $faker->word,
            $faker->word,
            $keyword,
        ];

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $news->withKeywords($keywords);
    }

    public function testWithKeywordsClonesObjectAndSetsValue()
    {
        $faker = $this->getFaker();

        $keywords = $faker->words;

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $instance = $news->withKeywords($keywords);

        $this->assertInstanceOf(News::class, $instance);
        $this->assertNotSame($news, $instance);
        $this->assertSame($keywords, $instance->keywords());
    }

    /**
     * @dataProvider \Refinery29\Test\Util\DataProvider\InvalidString::data()
     *
     * @param mixed $stockTicker
     */
    public function testWithStockTickersRejectsInvalidValues($stockTicker)
    {
        $this->expectException(\InvalidArgumentException::class);

        $faker = $this->getFaker();

        $stockTickers = [
            $faker->word,
            $faker->word,
            $stockTicker,
        ];

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $news->withStockTickers($stockTickers);
    }

    /**
     * @dataProvider \Refinery29\Test\Util\DataProvider\BlankString::data()
     *
     * @param mixed $stockTicker
     */
    public function testWithStockTickersRejectsBlankValues($stockTicker)
    {
        $this->expectException(\InvalidArgumentException::class);

        $faker = $this->getFaker();

        $stockTickers = [
            $faker->word,
            $faker->word,
            $stockTicker,
        ];

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $news->withStockTickers($stockTickers);
    }

    public function testWithStockTickersRejectsTooManyValues()
    {
        $this->expectException(\InvalidArgumentException::class);

        $faker = $this->getFaker();

        $stockTickers = $faker->words(NewsInterface::STOCK_TICKERS_MAX_COUNT + 1);

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $news->withStockTickers($stockTickers);
    }

    public function testWithStockTickersClonesObjectAndSetsValue()
    {
        $faker = $this->getFaker();

        $stockTickers = $faker->words(NewsInterface::STOCK_TICKERS_MAX_COUNT);

        $news = new News(
            $this->getPublicationMock(),
            $faker->dateTime,
            $faker->sentence()
        );

        $instance = $news->withStockTickers($stockTickers);

        $this->assertInstanceOf(News::class, $instance);
        $this->assertNotSame($news, $instance);
        $this->assertSame($stockTickers, $instance->stockTickers());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|PublicationInterface
     */
    private function getPublicationMock()
    {
        return $this->createMock(PublicationInterface::class);
    }
}
