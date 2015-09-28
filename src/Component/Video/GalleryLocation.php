<?php

namespace Refinery29\Sitemap\Component\Video;

final class GalleryLocation implements GalleryLocationInterface
{
    /**
     * @var string
     */
    private $location;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @param string      $location
     * @param string|null $title
     */
    public function __construct($location, $title = null)
    {
        $this->location = $location;
        $this->title = $title;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
