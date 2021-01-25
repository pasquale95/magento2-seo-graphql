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
 * Interface OpenGraphInterface
 * @package Paskel\Seo\Api\Data
 */
interface OpenGraphInterface extends SocialMarkupInterface
{
    /**
     * Constants for the html tags used to
     * crate an Open Graph meta tag
     */
    const TAG_PROPERTY = "property";
    const TAG_CONTENT = "content";

    /**
     * Constants defined for keys of array.
     * OpenGraph based.
     */
    const TYPE = "og:type";
    const LOCALE = "og:locale";
    const SITE = "og:site_name";
    const URL = "og:url";
    const TITLE = "og:title";
    const DESCRIPTION = "og:description";
    const IMAGE = "og:image";

    /**
     * Constants for those values that don't change among the graphql calls
     */
    const TYPE_VALUE = "website";
    const SITENAME_VALUE = "example";
}