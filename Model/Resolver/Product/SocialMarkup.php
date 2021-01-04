<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Resolver\Product;

use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\UrlInterface;
use Paskel\Seo\Helper\Url;
use Paskel\Seo\Helper\Url as UrlHelper;
use Paskel\Seo\Model\SocialMarkup\AbstractSocialMarkup;

/**
 * Class SocialMarkup
 * @package Paskel\Seo\Model\Resolver\Product
 *
 * Class to resolve socialMarkup field in product GraphQL query.
 */
class SocialMarkup extends AbstractSocialMarkup implements ResolverInterface
{
    /**
     * Fetches the data from persistence models and format it according to the GraphQL schema.
     *
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|Value
     * @throws LocalizedException
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        // Raise exception if no product model in the request
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }
        // retrieve product
        $product = $value['model'];
        // retrieve store
        $store = $context->getExtensionAttributes()->getStore();
        // initialise socialMarkups
        $this->socialMarkups = [];

        // add type
        $this->setType(self::TYPE_VALUE);
        // add locale
        $this->setLocale($this->storeConfigDataProvider->getStoreConfigData($store)['locale']);
        // add site_name
        $this->setSitenameByStore($store->getId());
        // add url
        $this->setUrl($this->retrieveUrl($product->getId(), ProductUrlRewriteGenerator::ENTITY_TYPE, $store->getId()));
        // add title
        $this->setTitle($product->getMetaTitle() ?? $product->getName());
        // add description
        $this->setDescription($product->getMetaDescription() ?? $product->getDescription());
        // add image, if any
        $this->setImage($this->retrieveImage($product, $store->getId(), $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)));

        return $this->socialMarkups;
    }

    /**
     * Retrieve category image url.
     * If not, use placeholder image.
     *
     * @param $product
     * @param $storeId
     * @param $storeUrl
     * @return string
     */
    public function retrieveImage($product, $storeId, $storeUrl) {
        // TODO: fix adding product repo and retrieve the image correctly
        $imageUrl = $product->getImage();
        if (isset($imageUrl) and !empty($imageUrl)) {
            return Url::pinchUrl($storeUrl . 'catalog/product', $imageUrl);
        } else {
            // return placeholder
            return UrlHelper::pinchUrl(
                $storeUrl . self::PLACEHOLDER_FOLDER,
                $this->socialMarkupHelper->getImagePlaceholder(
                    ProductUrlRewriteGenerator::ENTITY_TYPE,
                    $storeId
                )
            );
        }
    }
}
