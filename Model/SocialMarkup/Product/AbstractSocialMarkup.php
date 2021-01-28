<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\SocialMarkup\Product;

use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\Framework\UrlInterface;
use Magento\Catalog\Model\Product;
use Paskel\Seo\Api\Data\SocialMarkupInterface;
use Paskel\Seo\Helper\SocialMarkup as SocialMarkupHelper;
use Paskel\Seo\Helper\Url as UrlHelper;

/**
 * Class AbstractSocialMarkup
 * @package Paskel\Seo\Model\SocialMarkup\Product
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
     * Returns the product title.
     *
     * @param Product $product
     * @return string
     */
    protected function getTitle($product) {
        return $product->getMetaTitle() ?? $product->getName();
    }

    /**
     * Returns the product description.
     *
     * @param Product $product
     * @return string
     */
    protected function getDescription($product) {
        return $product->getMetaDescription() ?? $product->getDescription();
    }

    /**
     * Returns the product image.
     * If not set, use placeholder image.
     *
     * @param Product $product
     * @param $store
     * @return string
     */
    protected function getImage($product, $store) {
        // retrieve store info
        $storeId = $store->getId();
        $storeMediaUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        $imageUrl = $product->getImage();
        if (!empty($imageUrl)) {
            $imageUrl = UrlHelper::pinchUrl($storeMediaUrl . 'catalog/product', $imageUrl);
        } else {
            $imageUrl = $this->socialMarkupHelper->getImagePlaceholder(
                ProductUrlRewriteGenerator::ENTITY_TYPE,
                $storeId
            );
            if (!empty($imageUrl)) {
                // return placeholder
                $imageUrl = UrlHelper::pinchUrl($storeMediaUrl . self::PLACEHOLDER_FOLDER, $imageUrl);
            }
        }
        return $imageUrl ?? null;
    }
}
