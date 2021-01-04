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
 * Interface SocialMarkupInterface
 * @package Paskel\Seo\Api\Data
 */
interface SocialMarkupInterface
{
    /**
     * Constants for the Resolver array
     */
    const PROPERTY = "property";
    const CONTENT = "content";

    /**
     * Constants defined for keys of array.
     * OpenGraph based.
     */
    const TYPE = "og:type";
    const LOCALE = "og:locale";
    const SITENAME = "og:site_name";
    const URL = "og:url";
    const TITLE = "og:title";
    const DESCRIPTION = "og:description";
    const IMAGE = "og:image";

    /**
     * Name for the db field where to store the
     * image url.
     */
    const IMAGE_FIELD_DB = "social_markup_image";

    /**
     * Relative path where placeholder images are stored.
     */
    const PLACEHOLDER_FOLDER = "seo/socialMarkup/placeholder";

    /**
     * Constants for those values that don't change among the graphql calls
     */
    const TYPE_VALUE = "website";
    const SITENAME_VALUE = "example";

    /**
     * @param $type
     */
    public function setType($type);

    /**
     * @param $locale
     */
    public function setLocale($locale);

    /**
     * @param $sitename
     */
    public function setSitename($sitename);

    /**
     * @param $url
     */
    public function setUrl($url);

    /**
     * @param $title
     */
    public function setTitle($title);

    /**
     * @param $description
     */
    public function setDescription($description);

    /**
     * @param $image
     */
    public function setImage($image);
}