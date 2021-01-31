<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Api\Data;

/**
 * Interface TwitterCardInterface
 * @package Paskel\Seo\Api\Data
 */
interface TwitterCardInterface extends SocialMarkupInterface
{
    /**
     * Constants for the html tags used to
     * crate an Open Graph meta tag
     */
    const TAG_NAME = "name";
    const TAG_CONTENT = "content";

    /**
     * Constants defined for keys of array.
     * OpenGraph based.
     */
    const CARD = "twitter:card";
    const SITE = "twitter:site";
    const TITLE = "twitter:title";
    const DESCRIPTION = "twitter:description";
    const IMAGE = "twitter:image";
}