<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */
namespace Refinery29\Sitemap\Component\News;

use Assert\Assertion;
use DateTimeInterface;

final class News implements NewsInterface
{
    /**
     * @var PublicationInterface
     */
    private $publication;

    /**
     * @var DateTimeInterface
     */
    private $publicationDate;

    /**
     * @var string
     */
    private $title;

    /**
     * @var bool
     */
    private $access;

    /**
     * @var array
     */
    private $genres = [];

    /**
     * @var array
     */
    private $keywords = [];

    /**
     * @var array
     */
    private $stockTickers = [];

    /**
     * @param PublicationInterface $publication
     * @param DateTimeInterface    $publicationDate
     * @param string               $title
     * @param string|null          $access
     * @param array                $genres
     * @param array                $keywords
     * @param array                $stockTickers
     */
    public function __construct(
        PublicationInterface $publication,
        DateTimeInterface $publicationDate,
        $title,
        $access = null,
        array $genres = [],
        array $keywords = [],
        array $stockTickers = []
    ) {
        $this->publication = $publication;
        $this->publicationDate = $publicationDate;
        $this->title = $title;

        $this->setAccess($access);
        $this->setGenres($genres);

        $this->keywords = $keywords;

        $this->setStockTickers($stockTickers);
    }

    public function publication()
    {
        return $this->publication;
    }

    public function publicationDate()
    {
        return clone $this->publicationDate;
    }

    public function title()
    {
        return $this->title;
    }

    /**
     * @param string|null $access
     */
    private function setAccess($access = null)
    {
        $choices = [
            NewsInterface::ACCESS_REGISTRATION,
            NewsInterface::ACCESS_SUBSCRIPTION,
        ];

        Assertion::nullOrChoice($access, $choices);

        $this->access = $access;
    }

    public function access()
    {
        return $this->access;
    }

    /**
     * @param array $genres
     */
    private function setGenres(array $genres = [])
    {
        $choices = [
            NewsInterface::GENRE_BLOG,
            NewsInterface::GENRE_OP_ED,
            NewsInterface::GENRE_OPINION,
            NewsInterface::GENRE_SATIRE,
            NewsInterface::GENRE_USER_GENERATED,
        ];

        Assertion::allChoice($genres, $choices);

        $this->genres = $genres;
    }

    public function genres()
    {
        return $this->genres;
    }

    public function keywords()
    {
        return $this->keywords;
    }

    /**
     * @param array $stockTickers
     */
    private function setStockTickers(array $stockTickers = [])
    {
        Assertion::lessOrEqualThan(count($stockTickers), NewsInterface::STOCK_TICKERS_MAX_COUNT);

        $this->stockTickers = $stockTickers;
    }

    public function stockTickers()
    {
        return $this->stockTickers;
    }
}
