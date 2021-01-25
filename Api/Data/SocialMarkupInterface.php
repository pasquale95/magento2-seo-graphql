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
     * Name for the db field where to store the
     * image url.
     */
    const IMAGE_FIELD_DB = "social_markup_image";

    /**
     * Relative path where placeholder images are stored.
     */
    const PLACEHOLDER_FOLDER = "seo/socialMarkup/placeholder";

    /**
     * @param $item
     * @param $store
     * @return array
     */
    public function getTags($item, $store);
}