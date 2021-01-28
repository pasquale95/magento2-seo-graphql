<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\SocialMarkup\Category;

use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;
use Magento\Framework\UrlInterface;
use Magento\Catalog\Model\Category;
use Paskel\Seo\Api\Data\SocialMarkupInterface;
use Paskel\Seo\Helper\SocialMarkup as SocialMarkupHelper;
use Paskel\Seo\Helper\Url as UrlHelper;

/**
 * Class AbstractSocialMarkup
 * @package Paskel\Seo\Model\SocialMarkup\Category
 */
abstract class AbstractSocialMarkup implements SocialMarkupInterface
{
    /**
     * @var array
     */
    protected $tags = [];

    /**
     * @var SocialMarkupHelper
     */
    protected SocialMarkupHelper $socialMarkupHelper;

    /**
     * AbstractSocialMarkup constructor.
     *
     * @param SocialMarkupHelper $socialMarkupHelper
     */
    public function __construct(
        SocialMarkupHelper $socialMarkupHelper
    ) {
        $this->socialMarkupHelper = $socialMarkupHelper;
    }

    /**
     * Returns the category title.
     *
     * @param Category $category
     * @return string
     */
    protected function getTitle($category) {
        return $category->getMetaTitle() ?? $category->getName();
    }

    /**
     * Returns the category description.
     *
     * @param Category $category
     * @return string
     */
    protected function getDescription($category) {
        return $category->getMetaDescription() ?? $category->getDescription();
    }

    /**
     * Returns the category image.
     * If not set, use placeholder image.
     *
     * @param Category $category
     * @param $store
     * @return string
     */
    protected function getImage($category, $store) {
        // retrieve store info
        $storeId = $store->getId();

        $imageUrl = $category->getImage();
        if (!empty($imageUrl)) {
            $imageUrl = UrlHelper::pinchUrl($store->getBaseUrl(), $imageUrl);
        } else {
            $imageUrl = $this->socialMarkupHelper->getImagePlaceholder(
                CategoryUrlRewriteGenerator::ENTITY_TYPE,
                $storeId
            );
            if (!empty($imageUrl)) {
                // return placeholder
                $storeMediaUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                $imageUrl = UrlHelper::pinchUrl($storeMediaUrl . self::PLACEHOLDER_FOLDER, $imageUrl);
            }
        }
        return $imageUrl ?? null;
    }
}
