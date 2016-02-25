<?php

/*
 * Copyright (c) 2016 Refinery29, Inc.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */
namespace Refinery29\Sitemap\Component\Video;

final class Uploader implements UploaderInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $info;

    /**
     * @param string      $name
     * @param string|null $info
     */
    public function __construct($name, $info = null)
    {
        $this->name = $name;
        $this->info = $info;
    }

    public function name()
    {
        return $this->name;
    }

    public function info()
    {
        return $this->info;
    }
}
